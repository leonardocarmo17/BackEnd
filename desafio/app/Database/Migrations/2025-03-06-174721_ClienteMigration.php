<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration; 

class ClienteMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_cliente'    => ['type' => 'INT', 'constraint' => 5, 'auto_increment' => true],
            'nome_cliente'  => ['type' => 'VARCHAR', 'constraint' => 30],
            'cpf_cnpj'      => ['type' => 'VARCHAR', 'constraint' => 20],
        ]);
        $this->forge->addPrimaryKey('id_cliente');
        $this->forge->createTable('cliente_tabela');
    }

    public function down()
    {
        $this->forge->dropTable('cliente_tabela');
    }
}
