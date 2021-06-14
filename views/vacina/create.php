<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacina */

$this->title = 'Cadastrar Vacina';
$this->params['breadcrumbs'][] = ['label' => 'Vacinas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacina-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
