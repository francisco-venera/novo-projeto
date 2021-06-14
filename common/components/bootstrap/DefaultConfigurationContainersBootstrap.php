<?php

namespace common\components\bootstrap;

use kartik\select2\Select2;
use yii\base\BootstrapInterface;
use yii\helpers\Html;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class DefaultConfigurationContainersBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \yii\base\Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$container->set(ActiveForm::class, [
            'validatingCssClass' => 'validated',
            'errorCssClass' => 'is-invalid',
            'successCssClass' => 'is-valid',
            'validationStateOn' => ActiveForm::VALIDATION_STATE_ON_INPUT,
        ]);

        \Yii::$container->set(\kartik\file\FileInput::class, [
            'pluginOptions' => [
                'language' => 'pt-BR',
                'showPreview' => false,
                'showCaption' => true,
                'showUpload' => false,
                'showRemove' => true,
                'browseLabel' => 'Selecionar...',
                'msgPlaceholder' => 'Nenhum arquivo selecionado...',
                'msgSelected' => '{n} Arquivo(s) selecionado(s)',
                'browseClass' => 'btn btn-square btn-wide btn-secondary',
                'browseIcon' => '',
                'removeLabel' => '',
                'removeClass' => 'btn btn-square btn-wide btn-secondary',
                'removeIcon' => Html::tag('i', null, ['class' => 'fa fa-trash']),
            ],
        ]);

        \Yii::$container->set(ActiveField::class, [
            'errorOptions' => ['class' => 'kt-font-danger'],
        ]);

        \Yii::$container->set(Select2::class, [
            'theme' => Select2::THEME_DEFAULT,
            'language' => 'pt_BR',
        ]);
    }
}