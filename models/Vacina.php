<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacina".
 *
 * @property int $id
 * @property string $nome
 * @property int $idEspecie
 *
 * @property Especie $idEspecie0
 * @property Vacinacao[] $vacinacaos
 */
class Vacina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'idEspecie'], 'required'],
            [['idEspecie'], 'default', 'value' => null],
            [['idEspecie'], 'integer'],
            [['nome'], 'string', 'max' => 60],
            [['idEspecie'], 'exist', 'skipOnError' => true, 'targetClass' => Especie::className(), 'targetAttribute' => ['idEspecie' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'idEspecie' => 'Espécie',
        ];
    }

    /**
     * Gets query for [[IdEspecie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdEspecie0()
    {
        return $this->hasOne(Especie::className(), ['id' => 'idEspecie']);
    }

    /**
     * Gets query for [[Vacinacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacinacaos()
    {
        return $this->hasMany(Vacinacao::className(), ['idVacina' => 'id']);
    }
}
