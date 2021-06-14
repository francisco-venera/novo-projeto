<?php

use yii\db\Migration;

/**
 * Class m210608_161836_criar_vacina
 */
class m210608_161836_criar_vacina extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('vacina', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(60)->notNull(),
            'idEspecie' => $this->integer()->notNull(),  
          ]);

        $this->addForeignKey('fk_tipo_especie', 'vacina', 'idEspecie', 'especie', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tipo_especie', 'vacina');
        $this->dropTable('vacina');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_161836_criar_vacina cannot be reverted.\n";

        return false;
    }
    */
}
