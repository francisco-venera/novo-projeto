<?php

/* @var $form yii\widgets\ActiveForm */

use yii\widgets\MaskedInput;
use app\models\Cliente;

/* @var $this \yii\web\View */
/* @var $model \yii\db\ActiveRecord */

?>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-2">
        <?= $form->field($model, 'cep', ['enableClientValidation' => false])->widget(MaskedInput::class, [
            'mask' => '9{5}-9{3}',
            'options' => ['data-search-zipcode' => true]
        ]); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <?= $form->field($model, 'estado')->dropDownList(Cliente::ARRAY_STATES,['prompt' => 'Selecione']); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <?= $form->field($model, 'cidade')->textInput(['maxlength' => true]); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <?= $form->field($model, 'bairro')->textInput(['maxlength' => true]); ?>
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6">
        <?= $form->field($model, 'rua')->textInput(['maxlength' => true]); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <?= $form->field($model, 'numero')->textInput(['maxlength' => true]); ?>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
        <?= $form->field($model, 'pais')->textInput(['maxlength' => true]); ?>
    </div>
</div>