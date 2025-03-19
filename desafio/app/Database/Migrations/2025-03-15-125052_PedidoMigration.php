<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PedidoMigration extends Migration
{
    public function up()
    {
        // Criação da tabela pedido_tabela com os campos necessários
        $this->forge->addField([
            'id_pedido'     => ['type' => 'INT', 'constraint' => 5, 'auto_increment' => true],
            'id_cliente'    => ['type' => 'INT', 'constraint' => 5, 'null' => false],  // Chave estrangeira para cliente_tabela
            'id_produto'    => ['type' => 'INT', 'constraint' => 5, 'null' => false],  // Chave estrangeira para produto_tabela
            'quantidade'    => ['type' => 'INT', 'constraint' => 3, 'null' => false],
            'data_pedido'   => ['type' => 'TIMESTAMP', 'null' => false],  // Alterado para TIMESTAMP
        ]);
        
        // Define a chave primária
        $this->forge->addPrimaryKey('id_pedido');
        
        // Define as chaves estrangeiras
        $this->forge->addForeignKey('id_cliente', 'cliente_tabela', 'id_cliente', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_produto', 'produto_tabela', 'id_produto', 'CASCADE', 'CASCADE');

        // Criação da tabela com o mecanismo InnoDB
        $this->forge->createTable('pedido_tabela', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        // Desfaz a criação da tabela, removendo as chaves estrangeiras e a tabela
        $this->forge->dropTable('pedido_tabela');
    }
}
