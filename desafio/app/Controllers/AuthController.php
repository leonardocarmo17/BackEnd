<?php 
namespace App\Controllers;

use App\Libraries\RespostaPronta;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel;

class AuthController extends ResourceController
{   
    public $resposta;
    public function __construct() {
        $this->model = new UserModel();
        $this->resposta = new RespostaPronta();
    }
    public function login()
    { 
        $data = $this->request->getJSON(true);
        $enviar = $this->model->login($data);
        return $this->response->setJSON($enviar);
    }
    
    public function registrar(){
        $data = $this->request->getJSON(true);
        $enviar = $this->model->registrar($data);
        return $this->response->setJSON($enviar); 
    }
    public function getUser(){   
        $header = $this->request->getHeaderLine('Authorization');
        $enviar = $this->model->getUser($header);
        return $this->response->setJSON($enviar);
    }
}
