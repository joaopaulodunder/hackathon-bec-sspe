<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uges extends Model
{
    protected $table = "uges";

    public function municipio()
    {
        return $this->belongsTo('App\Municipios', 'id_municipio', 'id');
    }

    public function orgao()
    {
        return $this->belongsTo('App\Orgaos', 'id_orgao', 'id');
    }

    public function gestor()
    {
        return $this->belongsTo('App\Gestoes', 'id_gestao', 'id');
    }

    public static function comboBox()
    {
        $dados = static::orderBy("nome")->get();
        return $dados;
    }

    public static function comboBox2($municipio)
    {
        $dados = static::where('id_municipio', $municipio)->orderBy("nome")->get()->toArray();
        return $dados;
    }
}