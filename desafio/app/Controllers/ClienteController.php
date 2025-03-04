<?php 
namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\RespostaPronta;

class ClienteController extends ResourceController{
    protected $format = 'json';
    protected $fileName = 'App\Models\ClienteModel';
    private $key;
    private $resposta;
    public function __construct() {
        $this->model = new ClienteModel();
        $this->key = getenv('JWT_SECRET');
        $this->resposta = new RespostaPronta();

    }
    public function index()
    {
    $limit = $this->request->getGet('limit');
    $page = $this->request->getGet('page');

    // Definir valores padrão seguros
    $limit = is_numeric($limit) && $limit > 0 ? (int)$limit : null;
    $page = is_numeric($page) && $page > 0 ? (int)$page : 1;

    $query = $this->model;
    $filters = $this->request->getGet();
    unset($filters['page'], $filters['limit']);

    // Pegando as colunas válidas da tabela
    $validColumns = $this->model->getFieldNames('cliente_tabela'); 

    foreach ($filters as $campo => $valor) {
        // Verifica se o campo existe na tabela antes de aplicar o filtro
        if (in_array($campo, $validColumns) && !empty($valor) && is_string($valor)) {
            $query = $query->like($campo, $valor);
        }
    }
        $total = $query->countAllResults(false); 
        if(!$limit){
            $dados = $this->model->findAll();
        }   
        $dados = $query->paginate($limit, 'default', $page);
        return $this->respond(RespostaPronta::resposta('GET', 'clientes/' , STATUS200 , [
            'Página' => $limit ? $page : null,
            'Página por ID' => $limit ? $limit : 'todos',
            'Total de dados' => $total,
        ], $dados ));
     
        // ?limit=10&page=2
    }
    

    // SHOW ID FEITO

    public function show($id = null){
        $pedido = $this->model->find($id);
        if(!$pedido){
            return $this->respond(RespostaPronta::resposta('GET','clientes/' . $id, STATUS404, 'ID ' . $id . ' inválido', $pedido));
        }
        return $this->respond(RespostaPronta::resposta('GET','clientes/' . $id, STATUS200, 'Dados retornados com sucesso', $pedido));
    }

    //  UPDATE FEITO

    public function update($id = null)
{
    if (!$id) {
        return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id, STATUS400, 'Nenhum ID fornecido', null));
    }

    $pedido = $this->model->find($id);
    if (!$pedido) {
        return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id, STATUS404, 'ID ' . $id . ' inválido'));
    }

    $data = $this->request->getJSON(true);
    if (empty($data)) {
        return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id, STATUS400, 'ID ' . $id . ' válido, mas os valores informados pelo usuário são nulos'));
    }

    $dadosPermitidos = ['nome', 'cpf_cnpj', 'razao_social'];

    // Verifica se foram enviados apenas campos permitidos
    $dadosRecebidos = array_keys($data);
    $dadosInvalidos = array_diff($dadosRecebidos, $dadosPermitidos);

    if (!empty($dadosInvalidos)) {
        return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id, STATUS400, 'Apenas os campos permitidos são: ' . implode(', ', $dadosPermitidos), null
        ));
    }

    // Se passou nas verificações, atualiza o cliente
    $this->model->update($id, $data);
    $dados = array_merge(['id' => $id], $data);
    return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id, STATUS200, 'ID ' . $id . ' atualizado com sucesso', $dados));
}


    // CREATE FEITO

    public function create(){
    
        $data = $this->request->getJSON(true);
        if(empty($data)){
            return $this->respond(RespostaPronta::resposta('POST', 'clientes/' , STATUS400 , 'nenhum dado enviado'));
        }
        $requiredFields = ['nome','cpf_cnpj','razao_social'];

        $dadosRecebidos = array_keys($data);
        foreach ($requiredFields as $field){
            if(!isset($data[$field])){
                return $this->respond(RespostaPronta::resposta('POST','clientes/', STATUS400, 'O Campo ' . $field . ' é obrigatório'));
            }
        }
        if($dadosRecebidos !== $requiredFields){
           return $this->respond(RespostaPronta::resposta('POST','clientes/', STATUS400, 'Apenas os dados ' . implode(', ', $requiredFields) . ' são permitidos'));
        }

        $this->model->insert($data);
        $pedidoId = $this->model->getInsertID(); 
        $dados = array_merge(['id' => $pedidoId], $data);
        return $this->respond(RespostaPronta::resposta('POST','clientes/', STATUS200, 'ID ' . $pedidoId . ' Criado com sucesso', $dados));
    }

    // DELETE FEITO

    public function delete($id = null){
        if(!$id){
            return $this->respond(RespostaPronta::resposta('PUT', 'clientes/' . $id , STATUS400, 'nenhum ID fornecido', null));
        }

        $pedido = $this->model->find($id);
        
        if(!$pedido ){
            return $this->respond(RespostaPronta::resposta('Delete', 'clientes/' . $id, STATUS404, 'ID '. $id .' não encontrado'));
        }
        $this->model->delete($id); 
        return $this->respond(RespostaPronta::resposta('Delete', 'clientes/' . $id , STATUS200, 'ID ' . $id . ' deletado com sucesso', $pedido));
        
    }
}