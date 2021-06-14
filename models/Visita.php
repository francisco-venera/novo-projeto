<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visita".
 *
 * @property int $id
 * @property string $data
 * @property int $idCliente
 * @property int $idAnimal
 *
 * @property Animal $idAnimal0
 * @property Cliente $idCliente0
 */
class Visita extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visita';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'idCliente', 'idAnimal'], 'required'],
            [['data'], 'safe'],
            [['idCliente', 'idAnimal'], 'default', 'value' => null],
            [['idCliente', 'idAnimal'], 'integer'],
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
            'idCliente' => 'Cliente',
            'idAnimal' => 'Animal',
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
