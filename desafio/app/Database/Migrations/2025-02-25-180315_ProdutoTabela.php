<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProdutoTabela extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'produto_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'preco' => [
                'type' => 'FLOAT',
                'constraint' => 10,
                'unsigned' => true
            ],
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ]
        ]);
        $this->forge->addPrimaryKey('produto_id');
        $this->forge->createTable('produto_tabela');
    }

    public function down()
    {
        $this->forge->dropTable('produto_tabela');
    }
}
