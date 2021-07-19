<?php

/** @var $model \app\models\reports\ReportCliente */
/** @var $dataProvider \yii\data\ArrayDataProvider */
/** @var $this \yii\web\View */

$this->title = 'Relatório de clientes';
$this->params['breadcrumbs'][] = ['label' => 'Relatório de clientes'];
?>

<div class="kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title float-left">
                            Relatório de clientes
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <?= $this->render('_search', [
                            'model' => $model,
                        ]
                    ); ?>
                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-sm"></div>
                    <?php
                    if ($dataProvider !== null) {
                        echo $this->render('_report',
                            [
                                'model' => $model,
                                'dataProvider' => $dataProvider
                            ]
                        );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

