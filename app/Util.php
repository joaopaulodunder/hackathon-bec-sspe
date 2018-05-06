<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Util extends Model
{
    public static function formatData($data)
    {
        if ($data) {
            if(strlen($data) > 10) {
                $data = substr($data, 0, -9);
            }
            $data_aux = explode("-", $data);
            $dt_format = $data_aux[2] . "/" . $data_aux[1] . "/" . $data_aux[0];
            return $dt_format;
        } else {
            return null;
        }
    }
    public static function  mask($val, $mask)
    {
        /*
         * Exemplos de uso
            echo mask($cnpj,'##.###.###/####-##'); //99.999.999/99-99
            echo mask($cpf,'###.###.###-##'); //999.999.999-99
            echo mask($cep,'#####-###'); //9999-999
            echo mask($data,'##/##/####'); //99/99/9999
        */
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++){
            if($mask[$i] == '#')
            {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else{
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}