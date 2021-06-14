<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Vacina */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vacina-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true]); ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <?= $form->field($model, 'idEspecie')->textInput(); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
