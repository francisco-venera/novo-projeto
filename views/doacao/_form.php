<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Doacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-2">
        <?= $form->field($model, 'data')->widget(DateControl::class, [
            'type' => DateControl::FORMAT_DATE,
            'widgetOptions' => [
                'removeButton' => false,
            ]
        ]) ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idAnimal')->textInput(); ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idCliente')->textInput(); ?>
        </div>
    </div>

    <?= $form->field($model, 'obs')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
