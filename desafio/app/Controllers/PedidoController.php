<?php

namespace App\Controllers;

use App\Libraries\RespostaPronta;
use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;
use App\Models\PedidoModel;
use App\Models\ProdutoModel;

date_default_timezone_set('America/Sao_Paulo');

class PedidoController extends ResourceController
{
    protected $format    = 'json';
    private $resposta;
    private $produto;
    private $cliente;
    public function __construct() {
        $this->model = new PedidoModel();
        $this->resposta = new RespostaPronta();
        $this->produto = new ProdutoModel();
        $this->cliente = new ClienteModel();
    }

    // Retorna todos os pedidos com detalhes de cliente e produto
    public function index() 
{
    $limit = $this->request->getGet('limit');
    $page = $this->request->getGet('page');

    $limit = is_numeric($limit) && $limit > 0 ? (int)$limit : null;
    $page = is_numeric($page) && $page > 0 ? (int)$page : 1;
    
    // Obtém os dados
    $query = $this->model->dadosPedidos();

    // Verifica se a consulta retornou algo
    if (!$query) {
        return $this->respond(RespostaPronta::resposta('GET','pedidos/' , STATUS404, 'Nenhum dado encontrado'));

    }

    $filters = $this->request->getGet();
    unset($filters['page'], $filters['limit']);

    $validColumns = $this->model->getFieldNames('pedido_tabela'); 

    // Se `dadosPedidos()` retorna um array
    if (is_array($query)) {
        foreach ($filters as $campo => $valor) {
            if (in_array($campo, $validColumns) && !empty($valor) && is_string($valor)) {
                $query = array_filter($query, function($item) use ($campo, $valor) {
                    return stripos($item[$campo], $valor) !== false;
                });
            }
        }
        $total = count($query);
        $dados = $limit ? array_slice($query, ($page - 1) * $limit, $limit) : $query;
    } 
    // Se `dadosPedidos()` retorna um Query Builder
    elseif ($query instanceof \CodeIgniter\Database\BaseBuilder) {
        foreach ($filters as $campo => $valor) {
            if (in_array($campo, $validColumns) && !empty($valor) && is_string($valor)) {
                $query = $query->like($campo, $valor);
            }
        }
        $total = $query->countAllResults(false);
        $dados = $query->limit($limit, ($page - 1) * $limit)->get()->getResult();
    } 
    else {
        return $this->respond(RespostaPronta::resposta('GET','pedidos/' , STATUS404, 'A API retornou um valor inválido'));

    }

    return $this->respond(RespostaPronta::resposta('GET', 'pedidos/', STATUS200, [
        'Página' => $limit ? $page : null,
        'Página por ID' => $limit ? $limit : 'todos',
        'Total de dados' => $total,
    ], $dados));
}

    // Completo
    public function show($id = null){
        if (!$id) {
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS400, 'Nenhum ID fornecido', null));
        }
        $pedido = $this->model->find($id);
        if(!$pedido){
            return $this->respond(RespostaPronta::resposta('GET','pedidos/' . $id, STATUS404, 'ID ' . $id . ' inválido', $pedido));
        }
        return $this->respond(RespostaPronta::resposta('GET','pedidos/' . $id, STATUS200, 'Todos os dados retornados com sucesso', $pedido));
    }
    public function create()
    {
        $data = $this->request->getJson(true);
        $requiredFields = ['produto_id', 'cliente_id', 'status', 'quantidade'];
    
        // Verifica se todos os campos obrigatórios estão presentes
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return $this->fail("O campo '$field' é obrigatório.");
            }
        }
        // Normaliza o status
        $produto = $this->produto->find($data['produto_id']);
        if (!$produto) {
        
           return $this->respond(RespostaPronta::resposta('POST','pedidos/',STATUS404,'Produto ID ' .$data['produto_id'] . ' não foi encontrado'));
        }
        $cliente = $this->cliente->find($data['cliente_id']);
        if (!$cliente) {
           
           return $this->respond(RespostaPronta::resposta('POST','pedidos/',STATUS404,'Cliente ID ' .$data['cliente_id'] . ' não foi encontrado'));
        }
        
      
        $statusPermitidos = [
            'em aberto' => 'Em Aberto',
            'cancelado' => 'Cancelado',
            'pago'      => 'Pago'
        ];
        $statusRecebido = strtolower($data['status']);

        if (!isset($statusPermitidos[$statusRecebido])){
            return $this->respond(RespostaPronta::resposta('POST', 'pedidos/', STATUS400, 'Status inválido'));
        }
        
        $newData = [
            'produto_id'  => $data['produto_id'],
            'cliente_id'  => $data['cliente_id'],
            'status'      => $statusPermitidos[$statusRecebido],
            'quantidade'  => $data['quantidade'],
            'data_pedido' => date('Y-m-d H:i:s')
        ];
        
        // Insere no banco e captura o ID gerado
        if (!$this->model->insert($newData)) {
            return $this->respond(RespostaPronta::resposta('POST','pedidos/', STATUS400, 'Erro ao criar o pedido'));
        }
    
        $pedidoId = $this->model->getInsertID(); 
        return $this->respond(RespostaPronta::resposta('POST','pedidos/', STATUS200, 'Pedido criado com sucesso! ID do pedido: '. $pedidoId , $newData));

    }
    // Completo
    public function update($id = null){
        if(empty($id)){
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS400, 'Nenhum ID fornecido', null));
        }
        $pedido = $this->model->find($id);
        if(!$pedido){
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS404, 'ID ' . $id . ' inválido'));
   
        }
        $data = $this->request->getJson(true);
        if (empty($data)) {
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS400, 'ID ' . $id . ' válido, mas os valores informados pelo usuário são nulos'));
        }
        
        $statusPermitidos = [
            'em aberto' => 'Em Aberto',
            'cancelado' => 'Cancelado',
            'pago'      => 'Pago'
        ];
        if((isset($data['status']) && count($data) > 1)){
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS400, ['message' => 'Apenas o campo  "status" é permitido' , 'valores_permitidos' => implode(', ', $statusPermitidos)]
        ));        }
        $statusRecebido = strtolower(trim($data['status']));
        if (!isset($statusPermitidos[$statusRecebido])) {
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id, STATUS400, ['message' => 'Valor inválido' , 'valores_permitidos' => implode(', ', $statusPermitidos)]
        )); 
        }

        $this->model->update($id, ['status' => $statusPermitidos[$statusRecebido]]);
        $dados = ['id' => $id, 'status' => $statusPermitidos[$statusRecebido]];
        return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id , STATUS200, 'ID ' . $id . ' atualizado com sucesso', $dados));
    }
    public function delete($id = null){
        if(!$id){
            return $this->respond(RespostaPronta::resposta('PUT', 'pedidos/' . $id , STATUS400, 'nenhum ID fornecido', null));
        }

        $pedido = $this->model->find($id);
        
        if(!$pedido ){
            return $this->respond(RespostaPronta::resposta('Delete', 'pedidos/' . $id, STATUS404, 'ID '. $id .' não encontrado'));
        }
        $this->model->delete($id); 
        return $this->respond(RespostaPronta::resposta('Delete', 'pedidos/' . $id , STATUS200, 'ID ' . $id . ' deletado com sucesso', $pedido));
        
    }
}
