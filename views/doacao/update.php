<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Doacao */

$this->title = 'Atualizar doação: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Doação', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="doacao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
