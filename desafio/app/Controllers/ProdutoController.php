<?php 
namespace App\Controllers;

use App\Libraries\RespostaPronta;
use App\Models\ProdutoModel;
use CodeIgniter\RESTful\ResourceController;
use PharIo\Version\GreaterThanOrEqualToVersionConstraint;

class ProdutoController extends ResourceController{
    protected $format = 'json';
    private $resposta;
    public function __construct() {
        $this->model = new ProdutoModel();
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
    $validColumns = $this->model->getFieldNames('produto_tabela'); 

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
        return $this->respond(RespostaPronta::resposta('GET', 'produto/' , STATUS200 , [
            'Página' => $limit ? $page : null,
            'Página por ID' => $limit ? $limit : 'todos',
            'Total de dados' => $total,
        ], $dados ));
     
        // ?limit=10&page=2
    }    
    public function show($id = null){
        $pedido = $this->model->find($id);
        if(!$pedido){
            return $this->respond(RespostaPronta::resposta('GET','produtos/' . $id, STATUS404, 'ID ' . $id . ' inválido', $pedido));
        }
        return $this->respond(RespostaPronta::resposta('GET','produtos/' . $id, STATUS200, 'Dados retornados com sucesso', $pedido));
    }
    public function create(){
        $data = $this->request->getJSON(true);
        if(empty($data)){
            return $this->respond(RespostaPronta::resposta('POST', 'produtos/' , STATUS400 , 'nenhum dado enviado'));
        }
        $requiredFields = ['preco','descricao'];

        $dadosRecebidos = array_keys($data);
        foreach ($requiredFields as $field){
            if(!isset($data[$field])){
                return $this->respond(RespostaPronta::resposta('POST','produtos/', STATUS400, 'O Campo ' . $field . ' é obrigatório'));
                
            }
        }
        if($dadosRecebidos !== $requiredFields){
           return $this->respond(RespostaPronta::resposta('POST','produtos/', STATUS400, 'Apenas os dados ' . implode(', ', $requiredFields) . ' são permitidos'));
        }

        $this->model->insert($data);
        $pedidoId = $this->model->getInsertID(); 
        $dados = array_merge(['id' => $pedidoId], $data);
        return $this->respond(RespostaPronta::resposta('POST','produtos/', STATUS200, 'ID ' . $pedidoId . ' Criado com sucesso', $dados));
    }
    public function update($id = null)
{
    if (!$id) {
        return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id, STATUS400, 'Nenhum ID fornecido', null));
    }

    $pedido = $this->model->find($id);
    if (!$pedido) {
        return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id, STATUS404, 'ID ' . $id . ' inválido'));
    }

    $data = $this->request->getJSON(true);
    if (empty($data)) {
        return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id, STATUS400, 'ID ' . $id . ' válido, mas os valores informados pelo usuário são nulos'));
    }

    $dadosPermitidos = ['preco', 'descricao'];

    // Verifica se foram enviados apenas campos permitidos
    $dadosRecebidos = array_keys($data);
    $dadosInvalidos = array_diff($dadosRecebidos, $dadosPermitidos);

    if (!empty($dadosInvalidos)) {
        return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id, STATUS400, 'Apenas os campos permitidos são: ' . implode(', ', $dadosPermitidos), null
        ));
    }

    // Se passou nas verificações, atualiza o cliente
    $this->model->update($id, $data);
    $dados = array_merge(['id' => $id], $data);
    return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id, STATUS200, 'ID ' . $id . ' atualizado com sucesso', $dados));
}
    public function delete($id = null){
    if(!$id){
        return $this->respond(RespostaPronta::resposta('PUT', 'produtos/' . $id , STATUS400, 'nenhum ID fornecido', null));
    }

    $pedido = $this->model->find($id);
    
    if(!$pedido ){
        return $this->respond(RespostaPronta::resposta('Delete', 'produtos/' . $id, STATUS404, 'ID '. $id .' não encontrado'));
    }
    $this->model->delete($id); 
    return $this->respond(RespostaPronta::resposta('Delete', 'produtos/' . $id , STATUS200, 'ID ' . $id . ' deletado com sucesso', $pedido));
    
}
}