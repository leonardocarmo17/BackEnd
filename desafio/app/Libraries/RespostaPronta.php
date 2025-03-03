<?php

namespace App\Libraries;

class RespostaPronta
{
    public static function resposta($metodo, $rota, $status, $mensagem,$retorno = null)
    {
        return [
            "parametro" => [
                "metodo" => $metodo,
                "rota" => $rota
            ],
            "cabecalho" =>[
                "status" => $status,
                "mensagem" => $mensagem
            ],
            "retorno" => $retorno 
        ];
    }
}