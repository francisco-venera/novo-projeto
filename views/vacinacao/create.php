<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacinacao */

$this->title = 'Cadastrar Vacinação';
$this->params['breadcrumbs'][] = ['label' => 'Vacinacaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacinacao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>