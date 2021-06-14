<?php

use yii\db\Migration;

/**
 * Class m210608_161511_criar_especie
 */
class m210608_161511_criar_especie extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('especie', [
            'id' => $this->primaryKey(),
            'tipoEspecie' => $this->string(60)->notNull(),  
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('especie');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210608_161511_criar_especie cannot be reverted.\n";

        return false;
    }
    */
}
