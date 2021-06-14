<?php

use yii\db\Migration;

/**
 * Class m210608_164752_criar_visita
 */
class m210608_164752_criar_visita extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('visita', [
            'id' => $this->primaryKey(),
            'data' => $this->dateTime()->notNull(),
            'idCliente' => $this->integer()->notNull(),
            'idAnimal' => $this->integer()->notNull(),   
          ]);

        $this->addForeignKey('fk_id_cliente', 'visita', 'idCliente', 'cliente', 'id');
        $this->addForeignKey('fk_id_animal', 'visita', 'idAnimal', 'animal', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_id_cliente', 'visita');
        $this->dropForeignKey('fk_id_animal', 'visita');
        $this->dropTable('visita');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_164752_criar_visita cannot be reverted.\n";

        return false;
    }
    */
}
