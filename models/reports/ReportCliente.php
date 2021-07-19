<?php

namespace app\models\reports;

use app\models\Cliente;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * Class ReportSupplier
 * @package app\models\reports
 */
class ReportCliente extends Model
{
    /** @var  */
    public $nomeCliente;

    /** @var boolean $formatLandscape*/
    public $formatLandscape = true;

    /** @var string $format */
    public $format = 'A4-L';

    /** @var  */
    public $type;

    /**
     * Regras.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['formatLandscape'], 'boolean'],
            [['nomeCliente'], 'safe'],
        ];
    }

    /**
     * @throws \Exception
     */
  
    public function attributeLabels()
    {
        return [
            'nomeCliente' => 'Cliente(s)',
        ];
    }

    /**
     * @return ArrayDataProvider
     */
    public function process()
    {
        $result = [];

        if ($this->validate()) {
            $result = $this->processReport();
        }

        if (!$this->formatLandscape) {
            $this->format = 'A4';
        }

        return $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => false,
            'pagination' => false,
        ]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    private function processReport()
    {
        $nomeCliente = Cliente::find()
            ->select(['nomeCliente', 'email', 'documento', 'fone', 'cep', 'rua', 'numero', 'bairro', 'estado', 'cidade', 'celular'])
            ->andFilterWhere(['like', 'nomeCliente', $this->nomeCliente]);


        return $nomeCliente->asArray()->all();
    }
}
