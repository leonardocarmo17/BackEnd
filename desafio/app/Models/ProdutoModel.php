<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model {
    protected $table      = 'produto_tabela';
    protected $primaryKey = 'id_produto';
    protected $allowedFields = ['nome_produto', 'preco'];
    protected $rota = 'produtos/';

    public function tabelaInformacao() {
        return [
            'table'         => $this->table,
            'primaryKey'    => $this->primaryKey,
            'allowedFields' => $this->allowedFields,
            'rota'          => $this->rota
        ];
    }
    
}
