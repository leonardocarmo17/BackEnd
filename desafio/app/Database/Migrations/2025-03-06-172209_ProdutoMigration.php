<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProdutoMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_produto'    => ['type' => 'INT', 'constraint' => 5, 'auto_increment' => true],
            'nome_produto'   => ['type' => 'VARCHAR', 'constraint' => 30],
            'preco'         => ['type' => 'FLOAT', 'constraint' => 10],
        ]); 
        $this->forge->addPrimaryKey('id_produto');
        $this->forge->createTable('produto_tabela');
    }

    public function down()
    {
        $this->forge->dropTable('produto_tabela');
    }
}
