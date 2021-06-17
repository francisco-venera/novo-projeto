<?php

use yii\db\Migration;

/**
 * Class m210608_145012_criar_cliente
 */
class m210608_145012_criar_cliente extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cliente', [
          'id' => $this->primaryKey(),
          'nomeCliente' => $this->string(60)->notNull(),  
          'documento' => $this->string(60)->notNull(),
          'fone' => $this->string(60),
          'celular' => $this->string(60),
          'email' => $this->string(60),
          'cep' => $this->string(60),
          'rua' => $this->string(60),
          'numero' => $this->integer(),
          'bairro' => $this->string(60),
          'cidade' => $this->string(60),
          'estado' => $this->string(60),
          'pais' => $this->string(60),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cliente');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_145012_criar_cliente cannot be reverted.\n";

        return false;
    }
    */
}
