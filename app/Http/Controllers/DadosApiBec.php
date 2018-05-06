<?php

namespace App\Http\Controllers;

use App\Itens;
use App\Licitacao;
use App\Modalidade;
use App\Orgaos;
use App\Ufs;
use App\UnidadesCompradoras;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class DadosApiBec extends Controller
{

    public static function getDataOcBec($oc)
    {
        $client = new Client(); //GuzzleHttp\Client
        $result = $client->get(sprintf('https://www.bec.sp.gov.br/BEC_API/api/OC/%s', $oc))->getBody()->getContents();

        foreach (json_decode($result) as $item) {

            if ($item->MODALIDADE == 'Pregão Eletrônico') {

                echo "Processando OC => " . $item->OC . "\n";

                // Cadastra a UF
                $dadosUf = array('uf' => $item->UF);
                $ufs = new Ufs();
                $existUf = $ufs->where($dadosUf)->first();
                if (!$existUf) {
                    $ufs->insert($dadosUf);
                }

                // Cadastra Orgao
                $cod_nativo_orgao = self::getNativeCodeOrgaoOnOc($item->OC);
                $dadosOrgao = array(
                    'nm_orgao' => $item->UNIDADE_COMPRADORA,
                    'cod_nativo' => $cod_nativo_orgao
                );

                $orgaos = new Orgaos();
                $existOrgao = $orgaos->where($dadosOrgao)->first();
                if (!$existOrgao) {
                    $orgaos->insert($dadosOrgao);
                }

                //Cadastra Unidades Compradoras
                $dadosUnidadesCompradoras = array(
                    'uge' => $cod_nativo_orgao
                );

                $unidadesCompradoras = new UnidadesCompradoras();
                $existUnidadesCompradoras = $unidadesCompradoras->where($dadosUnidadesCompradoras)->first();
                if (!$existUnidadesCompradoras) {
                    $existUnidadesCompradoras->insert($existUnidadesCompradoras);
                }

                //Cadastra Modalidade
                $dadosModalidade = array('nm_modalidade' => $item->MODALIDADE);
                $modalidade = new Modalidade();
                $existModalidade = $modalidade->where($dadosModalidade)->first();
                if (!$existModalidade) {
                    $modalidade->insert($dadosModalidade);
                }

                //Cadastrar Licitacao
                $dadosLicitacao = array(
                    'oc' => $item->OC,
                    'nu_pregao' => $item->DESC_ATA_GERADAPR->OCCompleta->NumeroPregao,
                    'nu_processo' => $item->DESC_ATA_GERADAPR->OCCompleta->NumeroProcesso,
                    'txt_objeto' => $item->DESC_ATA_GERADAPR->OCCompleta->Objeto,
                    'status_oc' => $item->DESC_ATA_GERADAPR->OCCompleta->StatusOC,
                    'qtd_itens' => $item->QTD_ITENS,
                    'json_search_api' => $result,
                    'ata_registro_preco' => ($item->DESC_ATA_GERADAPR->OCCompleta->AtaRegistroPreco) ? 'true' : 'false',
                    'dt_inicio' => self::formatDateBrToEua($item->DESC_ATA_GERADAPR->OCCompleta->DataInicio),
                    'dt_fim' => self::formatDateBrToEua($item->DT_FIM),
                    'id_unidades_compradoras' => (isset($existUnidadesCompradoras->id)) ? $existUnidadesCompradoras->id : $unidadesCompradoras->where($dadosUnidadesCompradoras)->first()->id,
                    'id_uf' => (isset($existUf->id)) ? $existUf->id : $ufs->where($dadosUf)->first()->id,
                    'id_orgao' => (isset($existOrgao->id)) ? $existOrgao->id : $orgaos->where($dadosOrgao)->first()->id,
                    'id_modalidade' => (isset($existModalidade->id)) ? $existModalidade->id : $modalidade->where($dadosModalidade)->first()->id,
                );

                $licitacao = new Licitacao();
                $existLicitacao = $licitacao->where($dadosLicitacao)->first();
                if (!$existLicitacao) {
                    $licitacao->insert($dadosLicitacao);
                }

                //Cadastrar Intens
                if (count($item->ITENS) > 0) {
                    $count = 0;
                    foreach ($item->ITENS as $dadosItem) {

                        $dadosItens = array(
                            'id_licitacao' => (isset($existLicitacao->id)) ? $existLicitacao->id : $licitacao->where($dadosLicitacao)->first()->id,
                            'nu_item' => $dadosItem->NR_SEQUENCIA_ITEM,
                            'cod_item' => $dadosItem->CD_ITEM,
                            'cod_class_item' => $dadosItem->CD_CLASSE_ITEM,
                            'descricao_class' => $dadosItem->DESCRICAO_CLASSE,
                            'descricao_item' => $dadosItem->DESCRICAO_ITEM,
                            'unidade_fornecimento' => $dadosItem->UNIDADE_FORNECIMENTO,
                            'qtd' => $dadosItem->QUANTIDADE,
                            'cnpj_vencedor' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->CNPJ,
                            'menor_valor' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->MenorValor,
                            'nm_fornecedor' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->NomeFornecedor,
                            'qtd_propostas' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->QtdePropostas,
                            'status_ato_decisorio' => (isset($item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->AtosDecisorios[0]->Andamento)) ? $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->AtosDecisorios[0]->Andamento : '',
                            'justificativa_ato_decisorio' => (isset($item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->AtosDecisorios[0]->Justificativa)) ? $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->AtosDecisorios[0]->Justificativa : '' ,
                            'qtd_propostas_desistencia' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->QtdePropostasDesistencia,
                            'qtd_proposta_entregues' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->QtdePropostasEntregues,
                            'qtd_proposta_classificadas' => $item->DESC_ATA_GERADAPR->OCCompleta->AndamentosItensGrupos[$count]->FichaItemGrupo->QtdePropostasClassificadas,
                        );

                        $itemModel = new Itens();
                        $existItem = $itemModel->where($dadosItens)->first();
                        if (!$existItem) {
                            $itemModel->insert($dadosItens);
                        }
                        $count++;
                    }
                }
            }
        }
    }

    public static function getNativeCodeOrgaoOnOc($oc)
    {
        return substr($oc, 0 ,6);
    }

    public static function formatDateBrToEua($date)
    {
        return date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $date)));
    }
}
