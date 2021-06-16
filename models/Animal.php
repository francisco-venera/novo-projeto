<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "animal".
 *
 * @property int $id
 * @property string $nome
 * @property int $idEspecie
 * @property string $cor
 * @property string $tamanho
 * @property string $raca
 *
 * @property Especie $idEspecie0
 * @property Doacao[] $doacaos
 * @property Vacinacao[] $vacinacaos
 * @property Visita[] $visitas
 */
class Animal extends \yii\db\ActiveRecord
{

    public static function selectData()
    {
        $animais = Animal::find()
            ->select(['id', 'nome'])
            ->orderBy('nome asc')
            ->all();

        if(!$animais) return ['' => 'Nenhuma animal cadastrado'];

        $return = ['' => 'Selecione'];
        foreach($animais as $animal) {
            $return[$animal->id] = $animal->nome;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'animal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'idEspecie', 'cor', 'tamanho', 'raca'], 'required'],
            [['idEspecie'], 'default', 'value' => null],
            [['idEspecie'], 'integer'],
            [['nome', 'cor', 'tamanho', 'raca'], 'string', 'max' => 60],
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
            'idEspecie' => 'Especie',
            'cor' => 'Cor',
            'tamanho' => 'Tamanho',
            'raca' => 'Raça',
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
     * Gets query for [[Doacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoacaos()
    {
        return $this->hasMany(Doacao::className(), ['idAnimal' => 'id']);
    }

    /**
     * Gets query for [[Vacinacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacinacaos()
    {
        return $this->hasMany(Vacinacao::className(), ['idAnimal' => 'id']);
    }

    /**
     * Gets query for [[Visitas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitas()
    {
        return $this->hasMany(Visita::className(), ['idAnimal' => 'id']);
    }
}
