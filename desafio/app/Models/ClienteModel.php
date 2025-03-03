<?php 
namespace App\Models;

use App\Libraries\RespostaPronta;
use CodeIgniter\Model;

class ClienteModel extends Model{
    protected $table      = 'cliente_tabela';
    protected $primaryKey = 'cliente_id';
    protected $allowedFields = ['nome','cpf_cnpj','razao_social'];
    
}