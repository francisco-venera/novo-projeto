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
 * @property Especie $especie
 *  @property tipoEspecie $tipoEspecie0
 * @property Vacinacao[] $vacinacaos
 */
class Vacina extends \yii\db\ActiveRecord
{

    
    public static function selectData()
    {
        $vacinas = Vacina::find()
            ->select(['id', 'nome'])
            ->orderBy('nome asc')
            ->all();

        if(!$vacinas) return ['' => 'Nenhuma vacina cadastrada'];

        $return = ['' => 'Selecione'];
        foreach($vacinas as $vacina) {
            $return[$vacina->id] = $vacina->nome;
        }

        return $return;
    }
    
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
    public function getEspecie()
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
