<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doacao".
 *
 * @property int $id
 * @property string $data
 * @property int $idAnimal
 * @property int $idCliente
 * @property string|null $obs
 *
 * @property Animal $idAnimal0
 * @property Cliente $idCliente0
 */
class Doacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'idAnimal', 'idCliente'], 'required'],
            [['data'], 'safe'],
            [['idAnimal', 'idCliente'], 'default', 'value' => null],
            [['idAnimal', 'idCliente'], 'integer'],
            [['obs'], 'string', 'max' => 60],
            [['idAnimal'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::className(), 'targetAttribute' => ['idAnimal' => 'id']],
            [['idCliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['idCliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'idAnimal' => 'Animal',
            'idCliente' => 'Cliente',
            'obs' => 'Observações',
        ];
    }

    /**
     * Gets query for [[IdAnimal0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdAnimal0()
    {
        return $this->hasOne(Animal::className(), ['id' => 'idAnimal']);
    }

    /**
     * Gets query for [[IdCliente0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCliente0()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'idCliente']);
    }
}
