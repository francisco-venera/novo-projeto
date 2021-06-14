<?php

use yii\db\Migration;

/**
 * Class m210608_164311_criar_animal
 */
class m210608_164311_criar_animal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('animal', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(60)->notNull(),
            'idEspecie' => $this->integer()->notNull(),
            'cor' => $this->string(60)->notNull(),
            'tamanho' => $this->string(60)->notNull(),
            'raca' => $this->string(60)->notNull(),  
          ]);

        $this->addForeignKey('fk_tipo_especie', 'animal', 'idEspecie', 'especie', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tipo_especie', 'animal');
        $this->dropTable('animal');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_164311_criar_animal cannot be reverted.\n";

        return false;
    }
    */
}
