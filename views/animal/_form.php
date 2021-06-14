<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Animal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="animal-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
        </div>
            <div class="col-xs-12 col-md-6">
                <?= $form->field($model, 'idEspecie')->textInput(); ?>
            </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'cor')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'tamanho')->textInput(); ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'raca')->textInput(); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
