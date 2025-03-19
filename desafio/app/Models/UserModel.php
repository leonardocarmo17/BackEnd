<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\RespostaPronta;
use Firebase\JWT\JWT; 
use Firebase\JWT\Key;

class UserModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome', 'email', 'senha','token_criado'];
    public $resposta;
    private $key; 

    public function __construct() {
        parent::__construct();
        $this->key = getenv('JWT_SECRET');
        $this->resposta = new RespostaPronta();
    }
    public function registrar($data = null){
        $dadosEsperados = ['nome', 'email', 'senha'];

        $dadosRecebidos = array_keys($data);

        if (empty($data)) {
            return $this->resposta->resposta('GET','registrar',STATUS404,'Envie apenas os dados: ' . implode(', ', $dadosEsperados), null);
        }
    
    
        foreach ($dadosEsperados as $campo) {
            if (!isset($data[$campo])) {
                return $this->resposta->resposta('GET','registrar', STATUS400,'O campo ' . $campo . ' é obrigatório.');
            }
        }
    
        $campoInvalido = array_diff($dadosRecebidos, $dadosEsperados);
        if (!empty($campoInvalido)) {
            return $this->resposta->resposta('GET','registrar',STATUS400,'Apenas os dados: ' . implode(', ', $dadosEsperados) . ' são aceitos.');
        }
    
        $usuarioExistente = $this->where('email', $data['email'])->first();
        if ($usuarioExistente) {
            return $this->resposta->resposta('GET','registrar',STATUS400,'E-mail já cadastrado.');
        }
        // Insere o usuário no banco de dados
        if (!$this->insert($data)) {
            var_dump($data);
            return $this->resposta->resposta('GET','registrar',STATUS500,'Erro ao registrar usuário.');
        }
        $id = $this->where('email', $data['email'])->first();
        $dadosUsuario = [
            'id'    => $id['id'],
            'nome'  => $id['nome'],
            'email' => $id['email']
        ];
        return $this->resposta->resposta('GET','registrar',STATUS200,'Usuário registrado com sucesso! Vá para /login e faça seu login', $dadosUsuario);
    
    }
    public function gerarToken($usuarioId, $nome, $email)
    {
        // Simulação de dados do usuário (pode vir do banco de dados)
        $tempoExpiracao = time() + 7200; 

        $payload = [
            "iat" => time(),         // Tempo que o token foi gerado
            "exp" => $tempoExpiracao, // Tempo expiração
            "sub" => $usuarioId,
            "email" => $email, 
            "nome" => $nome     
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }
    public function login($data)
    { 
     
        $dadosEsperados = ['email', 'senha'];
    
        foreach ($dadosEsperados as $campo) {
            if (!isset($data[$campo])) {
                return $this->resposta->resposta('POST', 'login', STATUS400, 'O campo ' . $campo . ' é obrigatório.');
            }
        }
    
        $dadosRecebidos = array_keys($data);
        $campoInvalido = array_diff($dadosRecebidos, $dadosEsperados);
    
        if (!empty($campoInvalido)) {
            return $this->resposta->resposta('POST', 'login', STATUS400, 'Apenas os dados: ' . implode(', ', $dadosEsperados) . ' são aceitos.');
        }
    
        $usuario = $this->where('email', $data['email'])->first();
        if ($usuario) {
            $token = $this->gerarToken($usuario['id'], $usuario['nome'], $usuario['email']);
            $this->update($usuario['id'], ['token_criado' => $token]);
        }
    
        if (!$usuario || $data['senha'] !== $usuario['senha']) {
            return $this->resposta->resposta('POST', 'login',STATUS401, 'Usuário ou senha inválidos.');
        }
    
        // Gera o token para o usuário autenticado
        $token = $this->gerarToken($usuario['id'], $usuario['nome'], $usuario['email']);
        
        return $this->resposta->resposta('POST', 'login', STATUS200, $usuario['nome'] . ', seu token foi criado! Expira em 2 horas.', ['token' => $token]);
    }
    public function getUser($header){
   
    if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
        return $this->resposta->resposta('GET', 'user', STATUS401, 'Token não encontrado, não esqueça de utilizar o (Bearer "token") para funcionar');
    }

    $token = $matches[1]; // Obtém apenas o token sem o "Bearer"

    try {

        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        $som =   floor(($decoded->exp - time()) / 60);
        return $this->resposta->resposta('GET', 'user', STATUS200, $decoded->nome . ', autentificado com sucesso ' , ['ID' => $decoded->sub , 'nome' => $decoded->nome , 'email' => $decoded->email ,'tempo' => $som .' minutos restantes']);

    } catch (\Exception $e) {
        return $this->resposta->resposta('POST', 'user', STATUS401, 'Token não encontrado, não esqueça de utilizar o (Bearer "token") para funcionar');
    }
}
}
