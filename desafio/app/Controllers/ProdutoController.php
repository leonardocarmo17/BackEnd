<?php 
namespace App\Controllers;

use App\Libraries\RespostaPronta;
use App\Models\ProdutoModel;
use App\Models\Crud;
use CodeIgniter\RESTful\ResourceController;

class ProdutoController extends ResourceController{
    private $base;
    private $resposta;
    private $tabela;
    private $allowedFields;
    private $primaryKey;
    private $rota;
    
    public function __construct() {
        $this->model = new ProdutoModel();
        $tabela = $this->model->tabelaInformacao();
        $this->tabela = $tabela['table'];
        $this->allowedFields = $tabela['allowedFields'];
        $this->primaryKey = $tabela['primaryKey'];
        $this->rota = $tabela['rota'];
        $this->base = new Crud($this->tabela, $this->allowedFields, $this->primaryKey, $this->rota); 
        $this->resposta = new RespostaPronta();
    }
    
    public function index(){
        // MÉTODO GET ALL
        $limit = $this->request->getGet('limit');
        $page = $this->request->getGet('page');
        $enviar = $this->base->todosDados($limit, $page);
    
        return $this->response->setJSON($enviar);
    }
    public function show($id = null){
        // MÉTODO GET ID
        $enviar = $this->base->umDado($id);
        return $this->response->setJSON($enviar);
    }
    public function create(){
        // MÉTODO POST
        $dados = $this->request->getJSON(true);  
        $enviar = $this->base->enviarRegistro($dados);
        return $this->response->setJSON($enviar);
    }
    
    public function update($id = null, $dados = null){
        // MÉTODO PUT 
        $dados = $this->request->getJSON(true);
        $enviar = $this->base->editarRegistro($id,$dados);
        return $this->response->setJSON($enviar);
    }
    public function delete($id = null){
        // MÉTODO DELETE ID
        $enviar = $this->base->deleteDado($id);
        return $this->response->setJSON($enviar);
    }
}