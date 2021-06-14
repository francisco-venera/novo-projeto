<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Visita */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visita-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-2">
            <?= $form->field($model, 'data')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idCliente')->textInput(); ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idAnimal')->textInput(); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
