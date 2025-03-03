<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClienteTabela extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'cliente_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 30
            ],
            'cpf_cnpj' => [
                'type' => 'VARCHAR',
                'constraint' => 17,
            ],
            'razao_social' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ]
        ]);
        $this->forge->addPrimaryKey('cliente_id');
        $this->forge->createTable('cliente_tabela');
    }

    public function down()
    {
        $this->forge->dropTable('cliente_tabela');
    }
}
