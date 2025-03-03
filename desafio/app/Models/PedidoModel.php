<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidoModel extends Model
{
    protected $table      = 'pedido_tabela';
    protected $primaryKey = 'id';

    protected $allowedFields = ['produto_id', 'cliente_id', 'status', 'quantidade', 'data_pedido'];

    protected $useTimestamps = false; // Não usar timestamps automáticos


    public function dadosPedidos($id = null)    
    {
        $builder = $this->db->table($this->table)
            // Seleciona os dados Pedido
            ->select('pedido_tabela.*, cliente_tabela.nome AS cliente_nome, cliente_tabela.cpf_cnpj, produto_tabela.descricao, produto_tabela.preco')
            // Chama tabela do Cliente
            ->join('cliente_tabela', 'cliente_tabela.cliente_id = pedido_tabela.cliente_id')
            //Chama tabela de Produto
            ->join('produto_tabela', 'produto_tabela.produto_id = pedido_tabela.produto_id');
            // Se não estiver nulo, chama para o ID recebido
        if($id !== null){
            return $builder->where('pedido_tabela.pedido_id', $id)->get()->getRowArray();
        }
        return $builder->get()->getResultArray();
    
    }
}
