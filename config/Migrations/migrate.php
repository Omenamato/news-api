<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('words');
        $table
            ->addColumn('id', 'integer', [
                'limit' => 50,
                'null' => false,
                'auto_increment' => true,
            ])
            ->addColumn('searh', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('replace', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
        $this->execute(
            "INSERT INTO `words` VALUES " .
            "('', 'koleana', 'aurinkoisena', '', '')," .
            "('', 'lumikuuroja', 'auringonsäteitä', '', '')," .
            "('', 'koleaa', 'helteistä', '', '')," .
            "('', 'syyssää', 'kesäsää', '', '')," .
        );
    }
    public function down()
    {
        $this->dropTable('words');
    }
}