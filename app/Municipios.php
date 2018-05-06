<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    protected $table = "municipios";

    public function uges()
    {
        return $this->hasMany('App\Uges', 'id_municipio', 'id');
    }

    public static function comboBox()
    {
        $dados = static::where("codigo", ">", 0)->orderBy("nome")->get();
        return $dados;
    }
}
