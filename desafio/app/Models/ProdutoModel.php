<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\RespostaPronta;


class ProdutoModel extends Model
{
    protected $table      = 'produto_tabela';
    protected $primaryKey = 'produto_id';
    protected $allowedFields = ['preco', 'descricao'];

}