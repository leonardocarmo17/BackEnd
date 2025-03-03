<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'produto_id'  => ['type' => 'INT', 'constraint' => 11],
            'cliente_id'  => ['type' => 'INT', 'constraint' => 11],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20],
            'quantidade'  => ['type' => 'INT', 'constraint' => 11],
            'data_pedido' => ['type' => 'DATETIME', 'null' => false],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('pedido_tabela');
    }

    public function down()
    {
        $this->forge->dropTable('pedido_tabela');
    }
}
