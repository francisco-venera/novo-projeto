<?php

use yii\db\Migration;

/**
 * Class m210608_165812_criar_doacao
 */
class m210608_165812_criar_doacao extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('doacao', [
            'id' => $this->primaryKey(),
            'data' => $this->dateTime()->notNull(),
            'idAnimal' => $this->integer()->notNull(),
            'idCliente' => $this->integer()->notNull(),
            'obs' => $this->string(60),
          ]);

        $this->addForeignKey('fk_id_cliente', 'doacao', 'idCliente', 'cliente', 'id');
        $this->addForeignKey('fk_id_animal', 'doacao', 'idAnimal', 'animal', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_id_cliente', 'doacao');
        $this->dropForeignKey('fk_id_animal', 'doacao');
        $this->dropTable('doacao');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_165812_criar_doacao cannot be reverted.\n";

        return false;
    }
    */
}
