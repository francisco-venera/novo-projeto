<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vacinacao */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vacinação', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vacinacao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Excluir', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja excluir este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
        ],
    ]) ?>

</div>
