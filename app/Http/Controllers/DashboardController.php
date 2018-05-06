<?php

namespace App\Http\Controllers;

use View;
use App\Dados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;

class DashboardController extends Controller {

	/**
	 * Initializer.
	 *
	 * @return void
	 */
	public function __construct()
	{
	}

    public function index()
	{
//        $modelDados = new Dados();
//
//        $dados = $modelDados->getDados();
//        $listagem = $modelDados->processaListagem($dados);
//        $sinergia = $modelDados->processaSinergia($listagem);
//        $estatisticas = $modelDados->processaEstatisticas($sinergia);

        return view('dashboard.index')->with(array(
//            'listagem' => $listagem,
//            'sinergia' => $sinergia,
//            'estatisticas' => $estatisticas
        ));
    }


    public function filtro(Request $request)
    {
        $input = $request->all();
        if($input['custo']) {
            $custo = preg_replace('/\./', '', $input['custo']);
            $custo = preg_replace('/\,/', '.', $custo);
        }
        else{
            $custo = 0;
        }

        $modelDados = new Dados();

        $dados = $modelDados->getDadosFiltro($input['uge'], $input['periodo'], $custo);

//        $listagem = $modelDados->processaListagem($dados);

        $sinergia = $modelDados->processaSinergia($dados);

        $estatisticas = $modelDados->processaEstatisticas($sinergia);

        $global = [];
        foreach ($estatisticas as $periodos){
            foreach ($periodos as $estatistica){
                @$global['qtd_processo'] += $estatistica->qtd_processo;
                @$global['qtd'] += $estatistica->qtd;
                @$global['total_gasto_produto'] += $estatistica->total_gasto_produto;
                @$global['total_gasto_processo'] += $estatistica->total_gasto_processo;
                @$global['economia_processo'] += $estatistica->economia_processo;
                @$global['economia_produto_pelo_menor_preco'] += $estatistica->economia_produto_pelo_menor_preco;
            }
        }

        return view('dashboard.index')->with(array(
//            'listagem' => $listagem,
            'sinergia' => $sinergia,
            'estatisticas' => $estatisticas,
            'global_estatisticas' => $global
        ));
    }

    public function dataOrgao($gestao_id)
    {
        $dados = \App\HackUnidadesCompradora::comboBoxOrgao($gestao_id);

        $return = [];

        foreach ($dados as $dado){
            $return[] = [
                'id' => $dado['orgao'],
                'nome' => $dado['orgao']
            ];
        }
        return json_encode($return);
    }

    public function dataUge($orgao_id)
    {
        $dados = \App\HackUnidadesCompradora::comboBoxUge($orgao_id);

        $return = [];

        foreach ($dados as $dado){
            $return[] = [
                'id' => $dado['uge'],
                'nome' => $dado['uge'] . " - " . $dado['nome']
            ];
        }
        return json_encode($return);
    }
}