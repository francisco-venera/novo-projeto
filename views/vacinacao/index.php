<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\vacinacaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vacinação';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacinacao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cadastrar Vacinação', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'animal', 
                'value' => function($model) {
                    return $model->idAnimal ? $model->animal->nome : null;
                }
            ],
            [
                'attribute' => 'vacina', 
                'value' => function($model) {
                    return $model->idVacina ? $model->vacina->nome : null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
