<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacinacao */

$this->title = 'Atualizar vacinação: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Vacinação', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="vacinacao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
