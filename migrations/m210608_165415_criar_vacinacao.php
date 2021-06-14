<?php

use yii\db\Migration;

/**
 * Class m210608_165415_criar_vacinacao
 */
class m210608_165415_criar_vacinacao extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('vacinacao', [
            'id' => $this->primaryKey(),
            'data' => $this->dateTime()->notNull(),
            'idVacina' => $this->integer()->notNull(),
            'idAnimal' => $this->integer()->notNull(),   
          ]);

        $this->addForeignKey('fk_id_vacina', 'vacinacao', 'idVacina', 'vacina', 'id');
        $this->addForeignKey('fk_id_animal', 'vacinacao', 'idAnimal', 'animal', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_id_vacina', 'vacinacao');
        $this->dropForeignKey('fk_id_animal', 'vacinacao');
        $this->dropTable('vacinacao');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_165415_criar_vacinacao cannot be reverted.\n";

        return false;
    }
    */
}
