<?php

namespace App\Config;

class JwtConfig
{
    public static string $key = "sua_chave_secreta_aqui"; // Defina uma chave forte
    public static int $expTime = 3600; // Tempo de expiração em segundos (1 hora)
}
