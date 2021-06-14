<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id
 * @property string $nomeCliente
 * @property string $documento
 * @property int|null $fone
 * @property int|null $celular
 * @property string|null $email
 * @property string|null $cep
 * @property string|null $rua
 * @property int|null $numero
 * @property string|null $bairro
 * @property string|null $cidade
 * @property string|null $estado
 * @property string|null $pais
 *
 * @property Doacao[] $doacaos
 * @property Visita[] $visitas
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nomeCliente', 'documento'], 'required'],
            [['fone', 'celular', 'numero'], 'default', 'value' => null],
            [['fone', 'celular', 'numero'], 'integer'],
            [['nomeCliente', 'documento', 'email', 'cep', 'rua', 'bairro', 'cidade', 'estado', 'pais'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeCliente' => 'Nome Cliente',
            'documento' => 'Documento',
            'fone' => 'Fone',
            'celular' => 'Celular',
            'email' => 'E-mail',
            'cep' => 'Cep',
            'rua' => 'Rua',
            'numero' => 'Numero',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'estado' => 'Estado',
            'pais' => 'País',
        ];
    }

    /**
     * Gets query for [[Doacaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoacaos()
    {
        return $this->hasMany(Doacao::className(), ['idCliente' => 'id']);
    }

    /**
     * Gets query for [[Visitas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitas()
    {
        return $this->hasMany(Visita::className(), ['idCliente' => 'id']);
    }
}
