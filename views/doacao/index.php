<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DoacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cadastrar Doação', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'data',
                'format' => 'date',
            ],
            [
                'attribute' => 'cliente', 
                'value' => function($model) {
                    return $model->idCliente ? $model->cliente->nomeCliente : null;
                }
            ],
            [
                'attribute' => 'animal', 
                'value' => function($model) {
                    return $model->idAnimal ? $model->animal->nome : null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
