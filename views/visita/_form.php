<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use app\models\Animal;
use app\models\Cliente;

/* @var $this yii\web\View */
/* @var $model app\models\Visita */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visita-form">

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
        <?= $form->field($model, 'idCliente')->dropDownList(Cliente::selectData()); ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idAnimal')->dropDownList(Animal::selectData()); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
