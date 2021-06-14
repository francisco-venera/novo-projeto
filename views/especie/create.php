<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Especie */

$this->title = 'Cadastrar Espécie';
$this->params['breadcrumbs'][] = ['label' => 'Especies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="especie-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
