<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HackOrgaos extends Model
{
    protected $table = "hack_orgaos";

    protected $guarded = [];

    public static function comboBoxOrgao()
    {
        $dados = static::orderBy("nome")
            ->distinct()
            ->get(['codigo', 'nome']);
        return $dados;
    }

}