<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Cliente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'nomeCliente')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'documento',['enableClientValidation' => false])->widget(MaskedInput::class, [
                'mask' => ['9{3}.9{3}.9{3}-9{2}', '9{2}.9{3}.9{3}/9{4}-9{2}'],
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'email')->widget(MaskedInput::class, ['clientOptions' => ['alias' => 'email']]); ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'fone', ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'mask' => ['(9{2}) 9{4}-9{4}', '(9{2}) 9{5}-9{4}']
            ]); ?>
        </div>
        <div class="col-xs-12 col-md-3">
            <?= $form->field($model, 'celular', ['enableClientValidation' => false])->widget(MaskedInput::class, [
                'mask' => ['(9{2}) 9{4}-9{4}', '(9{2}) 9{5}-9{4}']
            ]); ?>
        </div>
    </div>

    <div class="row">
    
        <?= $this->render('/address/_form', ['form' => $form, 'model' => $model]); ?>

        

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
