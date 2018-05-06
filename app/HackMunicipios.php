<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HackMunicipios extends Model
{
    protected $table = "hack_municipios";

    protected $guarded = [];

    public static function comboBox()
    {
        $dados = static::orderBy("nome")->get();
        return $dados;
    }
}
