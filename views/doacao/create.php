<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Doacao */

$this->title = 'Cadastrar Doação';
$this->params['breadcrumbs'][] = ['label' => 'Doação', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
