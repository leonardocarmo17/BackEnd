<?php

namespace App\Filters;

use App\Libraries\RespostaPronta;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    protected $resposta;

    public function __construct()
    {
        $this->resposta = new RespostaPronta();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        $header = $request->getHeaderLine('Authorization');

        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return service('response')->setJSON(['error' => 'Token não encontrado'])->setStatusCode(401);
        }

        $token = $matches[1]; // Pegando apenas o JWT

        try {
            JWT::decode($token, new Key($key, 'HS256'));
            return; // Token válido, continua a requisição
        } catch (\Exception $e) {
            return service('response')->setJSON(['error' => 'Token inválido ou expirado'])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nenhuma ação necessária após a requisição
    }
}
