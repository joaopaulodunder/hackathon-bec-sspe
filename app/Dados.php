<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dados extends Model
{
    protected $table = "hack_tb_bec_lic_licitacao";

    protected $uges;

    protected $periodo_sinergia;

    protected $custo_processo;

    public function setUges($uges){
        $this->uges = $uges;
    }

    public function setPeriodoSinergia($orgao){
        $this->periodo_sinergia = $orgao;
    }

    public function setCustoProcesso($custo_processo){
        $this->custo_processo = $custo_processo;
    }

    public function getDadosFiltro($uges, $periodo_sinergia, $custo_processo)
    {
        if($uges) {
            $this->setUges($uges);
        }
        if($periodo_sinergia) {
            $this->setPeriodoSinergia($periodo_sinergia);
        }
        if($custo_processo) {
            $this->setCustoProcesso($custo_processo);
        }

        $result = $this->join('hack_tb_bec_lic_orgao as o', "o.id", "=", "hack_tb_bec_lic_licitacao.id_orgao")
            ->join('hack_tb_bec_lic_item', "hack_tb_bec_lic_item.id_licitacao", "=", "hack_tb_bec_lic_licitacao.id")
            ->where('hack_tb_bec_lic_licitacao.dt_inicio', ">", '2017-01-01 00:00:00')
            ->where('hack_tb_bec_lic_licitacao.dt_inicio', "<", '2017-12-31 00:00:00')
            ->where('hack_tb_bec_lic_item.status_ato_decisorio', "Adjudicado")
            ->whereIn('hack_tb_bec_lic_item.cod_item', [
                1324098,
                39896,
                4428692,
                3317935,
                120456,
                35610,
                4662270,
                108251,
                35629,
                4595718
            ])
            ->select(["o.nm_orgao as uge",
                "hack_tb_bec_lic_licitacao.oc as nu_licitacao", "hack_tb_bec_lic_licitacao.dt_inicio as data",
                "hack_tb_bec_lic_item.nu_item as nu_item", "hack_tb_bec_lic_item.descricao_item as nm_item",
                "hack_tb_bec_lic_item.cod_item", "hack_tb_bec_lic_item.qtd as nu_quant",
                "hack_tb_bec_lic_item.unidade_fornecimento as nm_unid_fornec",
                "hack_tb_bec_lic_item.status_ato_decisorio as status_item",
                "hack_tb_bec_lic_item.menor_valor as valor_vencedor",
                "hack_tb_bec_lic_item.nm_fornecedor as vencedor"
                ])
        ->limit(100);

        if($this->uges){
            $result = $result->whereIn('o.cod_nativo', $this->uges);
        }

        return $result->get();
    }

    /**
     * Sinergia: Mesma Cidade, Mesmo Produto, Mesma Secretaria, Periodo estipulado.
     */
    public function processaSinergia($dados)
    {
        $sinergia = [];

        foreach ($dados as $processo) {

            $periodo = $this->processaPeriodoSinergia($processo->data);
            $sinergia[$processo->nm_item . " - " . $processo->nm_unid_fornec][$periodo][] = $processo;

        }

        return $sinergia;
    }

    private function processaPeriodoSinergia($data)
    {
        $mensal = [
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez'
        ];

        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);

        if($this->periodo_sinergia == 'mensal'){
            $periodo = $mensal[$mes] . "/" . $ano;
        }
        if($this->periodo_sinergia == 'bimestral'){
            if($mes == '01' || $mes == '02'){
                $periodo = '1ºBim ' . $ano;
            }
            if($mes == '03' || $mes == '04'){
                $periodo = '2ºBim ' . $ano;
            }
            if($mes == '05' || $mes == '06'){
                $periodo = '3ºBim ' . $ano;
            }
            if($mes == '07' || $mes == '08'){
                $periodo = '4ºBim ' . $ano;
            }
            if($mes == '09' || $mes == '10'){
                $periodo = '5ºBim ' . $ano;
            }
            if($mes == '11' || $mes == '12'){
                $periodo = '6ºBim ' . $ano;
            }
        }

        if($this->periodo_sinergia == 'trimestral'){
            if($mes == '01' || $mes == '02' || $mes == '03'){
                $periodo = '1ºTri ' . $ano;
            }
            if($mes == '04' || $mes == '05' || $mes == '06'){
                $periodo = '2ºTri ' . $ano;
            }
            if($mes == '07' || $mes == '08' || $mes == '09'){
                $periodo = '3ºTrimestre ' . $ano;
            }
            if($mes == '10' || $mes == '11' || $mes == '12'){
                $periodo = '4ºTri ' . $ano;
            }
        }

        if($this->periodo_sinergia == 'semestral'){
            if($mes == '01' || $mes == '02' || $mes == '03' || $mes == '04' || $mes == '05' || $mes == '06'){
                $periodo = '1ºSem ' . $ano;
            }
            if($mes == '07' || $mes == '08' || $mes == '09' || $mes == '10' || $mes == '11' || $mes == '12'){
                $periodo = '2ºSem ' . $ano;
            }
        }

        if($this->periodo_sinergia == 'anual'){
            $periodo = substr($data, 0, 4); //ano
        }

        return $periodo;
    }

    /**
     * Sinergia: Mesma Cidade, Mesmo Produto, Mesma Secretaria, Periodo estipulado.
     */
    public function processaEstatisticas($dados)
    {
        $estatisticas = [];

            foreach ($dados as $produto => $periodos) {

                foreach ($periodos as $periodo => $processos) {

                    $estatisticas[$produto][$periodo] = new \stdClass();

                    foreach ($processos as $processo) {

                        @$estatisticas[$produto][$periodo]->qtd_processo += 1;

                        if ($processo->valor_vencedor) {

                            $valor_vencedor = preg_replace('/\./', '', $processo->valor_vencedor);
                            $valor_vencedor = preg_replace('/\,/', '.', $valor_vencedor);

                            @$estatisticas[$produto][$periodo]->qtd += $processo->nu_quant;

                            if (!isset($estatisticas[$produto][$periodo]->menor) ||
                                $valor_vencedor < $estatisticas[$produto][$periodo]->menor
                            ) {
                                $estatisticas[$produto][$periodo]->menor = $valor_vencedor;
                            }

                            if (!isset($estatisticas[$produto][$periodo]->maior) ||
                                $valor_vencedor > $estatisticas[$produto][$periodo]->maior
                            ) {
                                $estatisticas[$produto][$periodo]->maior = $valor_vencedor;
                            }
//
                            @$estatisticas[$produto][$periodo]->total_gasto_produto += ($processo->nu_quant * $valor_vencedor);

                        }
                        else{
                            $estatisticas[$produto][$periodo]->qtd = 0;
                            $estatisticas[$produto][$periodo]->total_gasto_produto = 0;
                        }
                    }

                    if($this->custo_processo) {
                        $estatisticas[$produto][$periodo]->total_gasto_processo = @$estatisticas[$produto][$periodo]->qtd_processo * $this->custo_processo;

                        if(@$estatisticas[$produto][$periodo]->qtd_processo > 1) {
                            $estatisticas[$produto][$periodo]->economia_processo = (@$estatisticas[$produto][$periodo]->qtd_processo - 1) * $this->custo_processo;
                        }
                        else{
                            $estatisticas[$produto][$periodo]->economia_processo = 0;
                        }

                    }else{
                        $estatisticas[$produto][$periodo]->total_gasto_processo = 0;
                        $estatisticas[$produto][$periodo]->economia_processo = 0;
                    }

                    if(@$estatisticas[$produto][$periodo]->qtd_processo > 1) {

                        $menor = $estatisticas[$produto][$periodo]->menor;

                        $total_pelo_menor = ($menor * @$estatisticas[$produto][$periodo]->qtd);

                        $estatisticas[$produto][$periodo]->economia_produto_pelo_menor_preco = @$estatisticas[$produto][$periodo]->total_gasto_produto - $total_pelo_menor;
                    }
                    else{
                        $estatisticas[$produto][$periodo]->economia_produto_pelo_menor_preco = 0;
                    }
                }
            }

        return $estatisticas;
    }

    public function diffDate($date1, $date2){

        $data1 = new \DateTime( $date1);
        $data2 = new \DateTime( $date2);

        $intervalo = $data1->diff( $data2);

        return $intervalo->days;
    }
}
