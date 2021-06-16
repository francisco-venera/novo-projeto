<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Doacao */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Doação', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="doacao-view">

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
                'vAlign' => 'middle',
                'hAlign' => 'middle',
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
            'obs',
        ],
    ]) ?>

</div>
