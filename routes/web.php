<?php

ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(1200);

use App\Dados;

use App\HackMunicipios;
use App\HackUnidadesCompradora;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
Route::get('/data-orgao/{gestao_id?}', array('as' => 'data-orgao', 'uses' => 'DashboardController@dataOrgao'));
Route::get('/data-uge/{orgao_id?}', array('as' => 'data-uge', 'uses' => 'DashboardController@dataUge'));

Route::post('/filtro', array('as' => 'filtro', 'uses' => 'DashboardController@filtro'));





Route::get('/depara', function () {

    $modelMunicipios = new HackMunicipios();
    $modelUnidadeCompradora = new HackUnidadesCompradora();

    $uges = \App\Uges::all();
    foreach($uges as $uge){

        $municipio_codigo = $uge->municipio->codigo;
        $municipio_nome = $uge->municipio->nome;
        $orgao_codigo = $uge->orgao->codigo;
        $gestor_orgao = $uge->gestor->codigo;
        $dados_uge = $uge->getAttributes();
//        dd($dados_uge);

        $found_municipio = $modelMunicipios->where("nome", $municipio_nome)->get();
        if(!isset($found_municipio[0])){

            $municipio = $modelMunicipios->create([
               "cod_nativo" =>  $municipio_codigo,
               "nome" =>  $municipio_nome
            ]);

        }else{
            $municipio = $found_municipio[0];
        }

        $found_uc = $modelUnidadeCompradora->where("uge", $dados_uge['uc'])->get();
        if(!isset($found_uc[0])) {

            $modelUnidadeCompradora->create([
                "municipio_id" => $municipio->id,
                "uge" => $dados_uge['uc'],
                "orgao" => $orgao_codigo,
                "gestao" => $gestor_orgao,
                "nome" => $dados_uge['nome'],
                "endereco" => $dados_uge['endereco'],
                "cep" => $dados_uge['cep'],
                "email" => $dados_uge['email'],
                "telefone1" => $dados_uge['telefone'],
                "telefone2" => $dados_uge['telefone'],
                "fax" => $dados_uge['fax'],
                "cnpj" => $dados_uge['cnpj']
            ]);
        }

    }

});

Route::get('/dev', function () {
//    return view('welcome');
//    DIE;

    $modelDados = new Dados();

    $dados = $modelDados->getDados();
    echo "<br> Total de Processos: " . count($dados);

    $listagem = $modelDados->processaListagem($dados);

    $sinergia = $modelDados->processaSinergia($listagem);

    $estatisticas = $modelDados->processaEstatisticas($sinergia);

//    echo "<pre>";
//    var_dump($eco);
//    echo "</pre>";

    echo "<h1>PAINEL - Estatisticas + Listagem com Sinergia</h1>";
    foreach ($estatisticas as $gestor => $cidades){

        echo "<h3>Gestor: ". $gestor."</h3>";

        foreach ($cidades as $cidade => $produtos){

            echo "<h3>Cidade: ". $cidade."</h3>";

            foreach ($produtos as $produto => $unids_fornecimento){

                echo "<h3>Produto: ". $produto."</h3>";

                foreach ($unids_fornecimento as $unid_fornecimento => $mesesAno) {

                    echo "<h3>Unidade Fornecimento: " . $unid_fornecimento . "</h3>";

                    foreach ($mesesAno as $mesAno => $estatistica) {

                        if (isset($estatistica->qtd)) {

                            echo "<h3>Mes ano: " . $mesAno . "</h3>";

                            echo "<h4>Estatisticas</h4>";
                            echo "QTD Processos: " . $estatistica->qtd_processo . " | Qtd unidades produto: " .
                                $estatistica->qtd . " | Menor Preco: " .
                                $estatistica->menor . " | Maior Preco: " .
                                $estatistica->maior . " | Total Gasto: " .
                                $estatistica->total_gasto . " | Media Preco: " .
                                $estatistica->media . " | Economia: " .
                                $estatistica->economia;

                            echo "<h4>Listagem com Sinergia Mensal</h4>";
                            foreach ($sinergia[$gestor][$cidade][$produto][$unid_fornecimento][$mesAno] as $processo) {

                                echo $processo->nu_licitacao . " | " .
                                    $processo->st_licitacao . " | " .
                                    $processo->nu_item . " | " .
                                    $processo->dt_abertura_proposta . " | " .
                                    $processo->uc . " | " .
                                    $processo->nm_municipio . " | " .
                                    $processo->nu_quant . " | " .
                                    $processo->nm_menor_valor . " | " .
                                    $processo->nm_unid_fornec;

                                echo "<br>";
                            }
                        }
                    }
                }
            }
        }

        echo "<br>-------------------------<br>";
    }

    die;

    echo "<h1>Estatisticas</h1>";
    foreach ($estatisticas as $gestor => $cidades){

        echo "<h3>Gestor: ". $gestor."</h3>";

        foreach ($cidades as $cidade => $produtos){

            echo "<h3>Cidade: ". $cidade."</h3>";

            foreach ($produtos as $produto => $unids_fornecimento){

                echo "<h3>Produto: ". $produto."</h3>";

                foreach ($unids_fornecimento as $unid_fornecimento => $mesesAno) {

                    echo "<h3>Unidade Fornecimento: " . $unid_fornecimento . "</h3>";

                    foreach ($mesesAno as $mesAno => $estatistica) {

                        echo "<h3>Mes ano: " . $mesAno . "</h3>";

                        if (isset($estatistica->qtd)) {

                            echo "QTD Processos: " . $estatistica->qtd_processo . " | Qtd unidades produto: " .
                                $estatistica->qtd . " | Menor Preco: " .
                                $estatistica->menor . " | Maior Preco: " .
                                $estatistica->maior . " | Total Gasto: " .
                                $estatistica->total_gasto . " | Media Preco: " .
                                $estatistica->media . " | Economia: " .
                                $estatistica->economia;

                            echo "<br>";
                        }
                    }
                }
            }
        }

        echo "<br>-------------------------<br>";
    }

    echo "<h1>Sinergia</h1>";
    foreach ($sinergia as $gestor => $cidades){

        echo "<h3>Gestor: ". $gestor."</h3>";

        foreach ($cidades as $cidade => $produtos){

            echo "<h3>Cidade: ". $cidade."</h3>";

            foreach ($produtos as $produto => $unids_fornecimento){

                echo "<h3>Produto: ". $produto."</h3>";

                foreach ($unids_fornecimento as $unid_fornecimento => $processos) {

                    echo "<h3>Unidade Fornecimento: " . $unid_fornecimento . "</h3>";

                    foreach ($processos as $mesAno => $processos) {

                        echo "<h3>Mes ano: " . $mesAno . "</h3>";

                        foreach ($processos as $processo) {

                            echo $processo->nu_licitacao . " | " .
                                $processo->st_licitacao . " | " .
                                $processo->nu_item . " | " .
                                $processo->dt_abertura_proposta . " | " .
                                $processo->uc . " | " .
                                $processo->nm_municipio . " | " .
                                $processo->nu_quant . " | " .
                                $processo->nm_menor_valor;

                            echo "<br>";
                        }
                    }
                }
            }
        }

        echo "<br>-------------------------<br>";
    }


    echo "<h1>Listagem</h1>";
    foreach ($listagem as $gestor => $cidades){

        echo "<h3>Gestor: ". $gestor."</h3>";

        foreach ($cidades as $cidade => $produtos){

            echo "<h3>Cidade: ". $cidade."</h3>";

            foreach ($produtos as $produto => $unids_fornecimento){

                echo "<h3>Produto: ". $produto."</h3>";

                    foreach ($unids_fornecimento as $unid_fornecimento => $processos) {

                        echo "<h3>Unidade Fornecimento: " . $unid_fornecimento . "</h3>";

                        foreach ($processos as $processo) {

                            echo $processo->nu_licitacao . " | " .
                                $processo->st_licitacao . " | " .
                                $processo->nu_item . " | " .
                                $processo->dt_abertura_proposta . " | " .
                                $processo->uc . " | " .
                                $processo->nm_municipio . " | " .
                                $processo->nu_quant . " | " .
                                $processo->nm_menor_valor . " | " .
                                $processo->nm_unid_fornec;

                            echo "<br>";
                        }
                    }
                }
            }

        echo "<br>-------------------------<br>";
    }
});
