<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacina */

$this->title = 'Atualizar Vacina: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Vacinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="vacina-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
