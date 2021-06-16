<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacinacao".
 *
 * @property int $id
 * @property string $data
 * @property int $idVacina
 * @property int $idAnimal
 *
 * @property idAnimal $idAnimal0
 * @property idVacina $idVacina0
 * @property Vacina $vacina
 * @property Animal $animal
 */
class Vacinacao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacinacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'idVacina', 'idAnimal'], 'required'],
            [['data'], 'safe'],
            [['idVacina', 'idAnimal'], 'default', 'value' => null],
            [['idVacina', 'idAnimal'], 'integer'],
            [['idAnimal'], 'exist', 'skipOnError' => true, 'targetClass' => Animal::className(), 'targetAttribute' => ['idAnimal' => 'id']],
            [['idVacina'], 'exist', 'skipOnError' => true, 'targetClass' => Vacina::className(), 'targetAttribute' => ['idVacina' => 'id']],
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
            'idVacina' => 'Vacina',
            'idAnimal' => 'Animal',
        ];
    }

    /**
     * Gets query for [[Vacina]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacina()
    {
        return $this->hasOne(Vacina::className(), ['id' => 'idVacina']);
    }

    /**
     * Gets query for [[Animal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimal()
    {
        return $this->hasOne(Animal::className(), ['id' => 'idAnimal']);
    }

    /**
     * Gets query for [[IdEspecie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEspecie()
    {
        return $this->hasOne(Especie::className(), ['id' => 'idEspecie']);
    }

    /**
     * Gets query for [[IdVacina0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdVacina0()
    {
        return $this->hasOne(Vacina::className(), ['id' => 'idVacina']);
    }
}
