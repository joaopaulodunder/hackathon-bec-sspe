<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HackUnidadesCompradora extends Model
{
    protected $table = "hack_unidades_compradora";

    protected $guarded = [];

    public static function comboBoxGestao()
    {
        $dados = static::orderBy("gestao")
            ->distinct()
            ->get(['gestao']);
        return $dados;
    }

    public static function comboBoxOrgao()
    {
        $dados = static::where('orgao', '>', 0)
            ->orderBy("orgao")
            ->distinct()
            ->get(['orgao']);
        return $dados;
    }

    public static function comboBoxUge($orgao_id)
    {
        $dados = static::where('orgao', $orgao_id)
            ->orderBy("nome")
            ->distinct()
            ->get(['id', 'uge', 'nome'])
            ->toArray();
        return $dados;
    }
}