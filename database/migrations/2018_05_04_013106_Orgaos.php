<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orgaos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hack_tb_bec_lic_orgao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cod_nativo');
            $table->string('nm_orgao');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hack_tb_bec_lic_orgao');
    }
}
