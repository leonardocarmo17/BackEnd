<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model{
    protected $table      = 'cliente_tabela';
    protected $primaryKey = 'id_cliente';
    protected $allowedFields = ['nome_cliente', 'cpf_cnpj'];
    protected $rota = 'clientes/';
    public function tabelaInformacao() {
        return [
            'table'         => $this->table,
            'primaryKey'    => $this->primaryKey,
            'allowedFields' => $this->allowedFields,
            'rota'          => $this->rota,
        ];
    }
}
