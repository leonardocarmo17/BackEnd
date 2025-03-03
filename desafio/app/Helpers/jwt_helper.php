<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Config\JwtConfig;

function generateJWT($userData)
{
    $payload = [
        "iss" => "seu_sistema",
        "iat" => time(),
        "exp" => time() + JwtConfig::$expTime,
        "data" => $userData
    ];

    return JWT::encode($payload, JwtConfig::$key, 'HS256');
}

function validateJWT($token)
{
    try {
        $decoded = JWT::decode($token, new Key(JwtConfig::$key, 'HS256'));
        return (array) $decoded->data;
    } catch (Exception $e) {
        return null;
    }
}
