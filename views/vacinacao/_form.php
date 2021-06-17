<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use app\models\Vacina;
use app\models\Animal;

/* @var $this yii\web\View */
/* @var $model app\models\Vacinacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vacinacao-form">

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
            <?= $form->field($model, 'idAnimal')->dropDownList(Animal::selectData()); ?>
        </div>
        <div class="col-xs-12 col-md-5">
            <?= $form->field($model, 'idVacina')->dropDownList(Vacina::selectData()); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
