<?php 
namespace App\Controllers;

use App\Libraries\RespostaPronta;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT; 
use Firebase\JWT\Key;
use App\Models\UserModel;

class AuthController extends ResourceController
{   
    public $resposta;
    private $key; 
    public function __construct() {
        $this->key = getenv('JWT_SECRET');
        $this->model = new userModel();
        $this->resposta = new RespostaPronta();
    }
    private function gerarToken($usuarioId, $nome, $email) 
    {
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
    public function validarToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256')); 
            return (array) $decoded; 
        } catch (\Exception $e) {
            return $this->respond(RespostaPronta::resposta('POST', 'user', STATUS401, 'Token inválido ou expirado'));
        }
    }
    public function login()
    { 
        $data = $this->request->getJSON(true);
    
        $dadosEsperados = ['email', 'senha'];
    
        foreach ($dadosEsperados as $campo) {
            if (!isset($data[$campo])) {
                return $this->respond(RespostaPronta::resposta('POST', 'login', STATUS400, 'O campo ' . $campo . ' é obrigatório.'));
            }
        }
    
        $dadosRecebidos = array_keys($data);
        $campoInvalido = array_diff($dadosRecebidos, $dadosEsperados);
    
        if (!empty($campoInvalido)) {
            return $this->respond(RespostaPronta::resposta('POST', 'login', STATUS400, 'Apenas os dados: ' . implode(', ', $dadosEsperados) . ' são aceitos.'));
        }
    
        $usuario = $this->model->where('email', $data['email'])->first();
        if ($usuario) {
            $token = $this->gerarToken($usuario['id'], $usuario['nome'], $usuario['email']);
            $this->model->update($usuario['id'], ['token_criado' => $token]);
        }
    
        if (!$usuario || $data['senha'] !== $usuario['senha']) {
            return $this->respond(RespostaPronta::resposta('POST', 'login',STATUS401, 'Usuário ou senha inválidos.'));
        }
    
        // Gera o token para o usuário autenticado
        $token = $this->gerarToken($usuario['id'], $usuario['nome'], $usuario['email']);
        
        return $this->respond(RespostaPronta::resposta('POST', 'login', STATUS200, $usuario['nome'] . ', seu token foi criado! Expira em 2 horas.', ['token' => $token]));

    }
    
public function registrar()
{
    $data = $this->request->getJSON(true);
    $dadosEsperados = ['nome', 'email', 'senha'];


    if (empty($data)) {
        return $this->respond(RespostaPronta::resposta('GET','registrar',STATUS404,'Envie apenas os dados: ' . implode(', ', $dadosEsperados), null));
    }

    $dadosRecebidos = array_keys($data);

    foreach ($dadosEsperados as $campo) {
        if (!isset($data[$campo])) {
            return $this->respond(RespostaPronta::resposta('GET','registrar', STATUS400,'O campo ' . $campo . ' é obrigatório.'));
        }
    }

    $campoInvalido = array_diff($dadosRecebidos, $dadosEsperados);
    if (!empty($campoInvalido)) {
        return $this->respond(RespostaPronta::resposta('GET','registrar',STATUS400,'Apenas os dados: ' . implode(', ', $dadosEsperados) . ' são aceitos.'));
    }

    $usuarioExistente = $this->model->where('email', $data['email'])->first();
    if ($usuarioExistente) {
        return $this->respond(RespostaPronta::resposta('GET','registrar',STATUS400,'E-mail já cadastrado.'));
    }
    // Insere o usuário no banco de dados
    if (!$this->model->insert($data)) {
        var_dump($data);
        return $this->respond(RespostaPronta::resposta('GET','registrar',STATUS500,'Erro ao registrar usuário.'));
    }
    $id = $this->model->where('email', $data['email'])->first();
    $dadosUsuario = [
        'id'    => $id['id'],
        'nome'  => $id['nome'],
        'email' => $id['email']
    ];;
    return $this->respond(RespostaPronta::resposta('GET','registrar',STATUS200,'Usuário registrado com sucesso! Vá para /login e faça seu login', $dadosUsuario));

    
}
public function getUser()
{
    $header = $this->request->getHeaderLine('Authorization');

    if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
        return $this->respond(RespostaPronta::resposta('GET', 'user', STATUS401, 'Token não encontrado, não esqueça de utilizar o (Bearer "token") para funcionar'));
    }

    $token = $matches[1]; // Obtém apenas o token sem o "Bearer"

    try {

        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        $som =   floor(($decoded->exp - time()) / 60);
        return $this->respond(RespostaPronta::resposta('GET', 'user', STATUS200, $decoded->nome . ', autentificado com sucesso ' , ['ID' => $decoded->sub , 'nome' => $decoded->nome , 'email' => $decoded->email ,'tempo' => $som .' minutos restantes']));

    } catch (\Exception $e) {
        return $this->respond(RespostaPronta::resposta('POST', 'user', STATUS401, 'Token não encontrado, não esqueça de utilizar o (Bearer "token") para funcionar'));
    }
}


}
