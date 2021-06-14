<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "especie".
 *
 * @property int $id
 * @property string $tipoEspecie
 *
 * @property Animal[] $animals
 * @property Vacina[] $vacinas
 */
class Especie extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'especie';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipoEspecie'], 'required'],
            [['tipoEspecie'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipoEspecie' => 'Tipo Espécie',
        ];
    }

    /**
     * Gets query for [[Animals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnimals()
    {
        return $this->hasMany(Animal::className(), ['idEspecie' => 'id']);
    }

    /**
     * Gets query for [[Vacinas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacinas()
    {
        return $this->hasMany(Vacina::className(), ['idEspecie' => 'id']);
    }
}
