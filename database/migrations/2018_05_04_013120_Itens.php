<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Itens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hack_tb_bec_lic_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_licitacao')->unsigned()->index()->nullable();
            $table->integer('nu_item');
            $table->integer('cod_item');
            $table->integer('cod_class_item');
            $table->longText('descricao_class');
            $table->longText('descricao_item');
            $table->longText('cnpj_vencedor');
            $table->longText('menor_valor');
            $table->longText('nm_fornecedor');
            $table->longText('status_ato_decisorio');
            $table->longText('justificativa_ato_decisorio');
            $table->string('unidade_fornecimento');
            $table->integer('qtd');
            $table->integer('qtd_propostas');
            $table->integer('qtd_propostas_desistencia');
            $table->integer('qtd_proposta_entregues');
            $table->integer('qtd_proposta_classificadas');
            $table->timestamps();

            $table->foreign('id_licitacao')->references('id')->on('hack_tb_bec_lic_licitacao');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hack_tb_bec_lic_item');
    }
}
