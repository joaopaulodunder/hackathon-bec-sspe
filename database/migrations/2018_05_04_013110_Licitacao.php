<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Licitacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hack_tb_bec_lic_licitacao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('oc');
            $table->string('nu_pregao');
            $table->string('nu_processo');
            $table->longText('txt_objeto');
            $table->longText('status_oc');
            $table->integer('qtd_itens');
            $table->longText('json_search_api');
            $table->enum('ata_registro_preco', ['true', 'false']);
            $table->dateTime('dt_inicio');
            $table->dateTime('dt_fim');
            $table->integer('id_unidades_compradoras')->unsigned()->index()->nullable();
            $table->integer('id_uf')->unsigned()->index()->nullable();
            $table->integer('id_orgao')->unsigned()->index()->nullable();
            $table->integer('id_modalidade')->unsigned()->index()->nullable();
            $table->timestamps();

            // Referencias de foreign key
            $table->foreign('id_unidades_compradoras')->references('id')->on('hack_unidades_compradora');
            $table->foreign('id_uf')->references('id')->on('hack_tb_bec_lic_ufs');
            $table->foreign('id_orgao')->references('id')->on('hack_tb_bec_lic_orgao');
            $table->foreign('id_modalidade')->references('id')->on('hack_tb_bec_lic_modalidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hack_tb_bec_lic_licitacao');
    }
}
