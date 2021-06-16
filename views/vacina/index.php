<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VacinaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vacinas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacina-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cadastrar Vacina', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nome',
            [
                'attribute' => 'tipoEspecie', 
                'value' => function($model) {
                    return $model->idEspecie ? $model->especie->tipoEspecie : null;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
