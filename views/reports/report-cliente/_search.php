<?php

use common\components\Layout;
use app\models\Cliente;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \frontend\models\reports\ReportClient */
/* @var $form \yii\widgets\ActiveForm */

?>
<div class="kt-form form-search filter-form">
    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            
            <div class="cliente-search">
            
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <?= $form->field($model, 'nomeCliente')->textInput(['maxlength' => true]); ?>
                    </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('Cancelar', ['class' => 'btn btn-outline-secondary']) ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-2 kt-margin-t-25">
                <?= $form->field(
                    $model,
                    'formatLandscape',
                )
                    ->checkbox(['style' => 'vertical-align:middle;',], false)
                    ->label('Impressão paisagem');
                ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
