<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\RespostaPronta;

class Crud extends Model {
    private $resposta;
    public $tabela;
    public $allowedFields;
    public $primaryKey;
    public $rota;
    public $pedido;

    public function __construct($tabela, $allowedFields, $primaryKey,$rota) {
        $this->resposta = new RespostaPronta();
        $this->tabela = $this->getModelInstance($tabela); 
        $this->allowedFields = $allowedFields;
        $this->primaryKey = $primaryKey;
        $this->rota = $rota;
        $this->pedido = new PedidoModel();
    }
    private function getModelInstance($tabela) {
        if ($tabela === 'cliente_tabela') {
            return new ClienteModel(); 
        }
        else if($tabela === 'produto_tabela'){
            return new ProdutoModel();
        }
        else if($tabela === 'pedido_tabela'){
            return new PedidoModel();
        }
        throw new \Exception("Modelo para a tabela $tabela não encontrado.");
    }    
    public function todosDados($limit = 10, $page = 1) {
        $limit = is_numeric($limit) && $limit > 0 ? (int)$limit : null;
        $page = is_numeric($page) && $page > 0 ? (int)$page : null;
        if($limit === null || $page === null ){
            if($this->tabela instanceof PedidoModel){
                $dados = $this->pedido->dadosPedidos(); 
            }
            else{
                $dados = $this->tabela->findAll();
            }
            return $this->resposta->resposta('GET',$this->rota, STATUS200,'Todos os dados recebidos com sucesso.' ,$dados);                  
        }
        $query = $this->tabela;
        $filters = $_GET; 
        unset($filters['page'], $filters['limit']); 
        $validColumns = $this->tabela->db->getFieldNames($this->tabela->table);

        foreach ($filters as $campo => $valor) {
            if (in_array($campo, $validColumns) && !empty($valor) && is_string($valor)) {
                $query = $query->like($campo, $valor);
            }
        }
        // Paginação via CodeIgniter
        $dados = $query->paginate($limit, 'default', $page);
        return $this->resposta->resposta('GET',$this->rota,'STATUS200', ['Limite por Página:' => $limit , 'Página:' => $page], $dados);                    
    }
    
    public function umDado($id = null) {
        $dados = $this->tabela->find($id);
        if($dados){
    
            return $this->resposta->resposta('GET',$this->rota .$id, STATUS200, 'Dados',$dados);
        }else{
            return $this->resposta->resposta('GET',$this->rota. $id, STATUS404, 'ID Inválido');
        }
    }

    public function enviarRegistro($dados = null) {
        
        $dadosRecebidos = array_keys($dados);
        
        foreach ($this->allowedFields as $field){
            if(!isset($dados[$field])){
                return $this->resposta->resposta('POST',$this->rota, STATUS400, 'O Campo ' . $field . ' é obrigatório');

            }
        }
        if($this->table === 'pedido_tabela'){
            $clienteModel = new ClienteModel();
            $pedidoModel = new PedidoModel();

            $cliente = $clienteModel->find($dados['id_cliente']);
            $produto = $pedidoModel->find($dados['id_produto']);
            if (empty($cliente)) {
                return $this->resposta->resposta('POST', 'pedidos/', STATUS404, 'Cliente ID ' . $dados['id_cliente'] . ' não foi encontrado');  
            }
            if (empty($produto)) {
                return $this->resposta->resposta('POST', 'pedidos/', STATUS404, 'Produto ID ' . $dados['id_produto'] . ' não foi encontrado');
            }
        }
        if(array_diff($dadosRecebidos,$this->allowedFields)){
           return $this->resposta->resposta('POST',$this->rota, STATUS400, 'Apenas os dados ' . implode(', ', $this->allowedFields) . ' são permitidos');
        }
        if ($this->tabela->insert($dados)) {
            $pedidoId = $this->tabela->getInsertID();
            
            $dado = array_merge([$this->primaryKey => $pedidoId], $dados);
            return $this->resposta->resposta('POST', $this->rota, STATUS200, 'ID ' . $pedidoId . ' Criado com sucesso', $dado);
        }
    
        return $this->resposta->resposta('POST', $this->rota, STATUS500, 'Erro ao inserir registro.');
    }

    public function editarRegistro($id = null, $dados = null) {
        if(empty($id)){
            return $this->resposta->resposta('PUT',$this->rota, 404, 'ID Não encontrado');
        }
        if(empty($dados)){
            return $this->resposta->resposta('PUT',$this->rota, 404, 'Apenas os campos permitidos são: ' . implode(', ', $this->allowedFields));
        }
        $dadosId = $this->tabela->find($id);
        if($dadosId){
            $dadosRecebidos = array_keys($dados);
            $dadosInvalidos = array_diff($dadosRecebidos, $this->allowedFields);
        
        if (!empty($dadosInvalidos)) {
            return $this->resposta->resposta('PUT', $this->rota . $id, 400, 'Apenas os campos permitidos são: ' . implode(', ', $this->allowedFields));

        }
        $this->tabela->update($id, $dados);
        $dado = array_merge([$this->primaryKey => (int) $id], $dados);
        return $this->resposta->resposta('PUT', $this->rota, 200, 'Atualizado com sucesso', $dado);
    } else {
        return $this->resposta->resposta('PUT', $this->rota . $id, 404, 'ID não encontrado',  [$this->primaryKey => (int) $id]);
    }
    }
    public function deleteDado($id = null) {
        if(empty($id)){
            return $this->resposta->resposta('PUT',$this->rota, 404, 'ID Não encontrado');
        }
        $idTrue = $this->tabela->find($id);
        if(!empty($idTrue)){
            if($this->tabela->delete($id)){
                return $this->resposta->resposta('delete',$this->rota . $id , STATUS200, 'ID Deletado com sucesso', [$this->primaryKey => (int) $id]);
            }
        }       
        return $this->resposta->resposta('delete',$this->rota. $id, STATUS400, 'ID Inválido', [$this->primaryKey => (int) $id]);
    }
}
