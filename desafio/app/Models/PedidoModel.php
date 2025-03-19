<?php
namespace App\Models;

use App\Models\ProdutoModel;
use App\Models\ClienteModel;
use CodeIgniter\Model;
date_default_timezone_set('America/Sao_Paulo');

class PedidoModel extends Model 
{
    protected $table      = 'pedido_tabela';
    protected $primaryKey = 'id_pedido';
    protected $allowedFields = ['id_cliente', 'id_produto', 'quantidade'];
    protected $rota = 'pedidos/';
    protected $useTimestamps = false;
    public $clienteModel;
    public $produtoModel;

    public function __construct() {
        parent::__construct(); 
        $this->produtoModel = new ProdutoModel();
        $this->clienteModel = new ClienteModel();
    }
    public function tabelaInformacao(){
        return [
            'table'         => $this->table,
            'primaryKey'    => $this->primaryKey,
            'allowedFields' => $this->allowedFields,
            'rota'          => $this->rota
        ];
    }
    public function dadosPedidos($id = null){
        $builder = $this->table($this->table)
            ->select('pedido_tabela.*, cliente_tabela.id_cliente, cliente_tabela.nome_cliente, cliente_tabela.cpf_cnpj, produto_tabela.id_produto, produto_tabela.nome_produto, produto_tabela.preco')
            ->join('cliente_tabela', 'cliente_tabela.id_cliente = pedido_tabela.id_cliente')
            ->join('produto_tabela', 'produto_tabela.id_produto = pedido_tabela.id_produto');
        
        if ($id !== null) {
            return $builder->where('pedido_tabela.id_pedido', $id)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
