<?php

namespace common\entities\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\entities\Cliente;

/**
 * ClienteSearch represents the model behind the search form of `common\entities\Cliente`.
 */
class ClienteSearch extends Cliente
{
    public $genericSearch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['genericSearch'], 'safe'],
            [['id', 'adminId', 'phone', 'createdBy', 'updatedBy'], 'integer'],
            [['name', 'email', 'document', 'obs', 'zipCode', 'state', 'city', 'district', 'street', 'number', 'complement', 'token', 'createdAt', 'updatedAt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return parent::attributeLabels() + ['genericSearch' => 'Buscar'];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Cliente::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
            'sort'=> ['defaultOrder' => ['name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'or',
            ['ilike', 'name', $this->genericSearch],
            ['ilike', 'email', $this->genericSearch],
            ['ilike', 'document', $this->genericSearch],
            ['ilike', 'obs', $this->genericSearch],
            ['ilike', 'zipCode', $this->genericSearch],
            ['ilike', 'state', $this->genericSearch],
            ['ilike', 'city', $this->genericSearch],
            ['ilike', 'district', $this->genericSearch],
            ['ilike', 'street', $this->genericSearch],
            ['ilike', 'number', $this->genericSearch],
            ['ilike', 'complement', $this->genericSearch]
        ]);

        return $dataProvider;
    }
}
