<?php

use common\components\Layout;
use yii\grid\GridView;
use common\entities\Cliente;

/* @var $this yii\web\View */
/* @var $model \frontend\models\reports\ReportClient */
/* @var $dataProvider \yii\data\ArrayDataProvider|null */

$reportTitle = "Relatório de clientes";
$filename = "relatorio_clientes";

echo GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'Nome',
                'attribute' => 'nomeCliente',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Documento',
                'attribute' => 'documento',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Telefone',
                'attribute' => 'fone',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [   
                'label' => 'Celular',
                'attribute' => 'celular',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'E-mail',
                'attribute' => 'email',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'CEP',
                'attribute' => 'cep',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Estado',
                'attribute' => 'estado',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Cidade',
                'attribute' => 'cidade',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Bairro',
                'attribute' => 'bairro',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Rua',
                'attribute' => 'rua',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
            [
                'label' => 'Número',
                'attribute' => 'numero',
                'headerOptions' => ['style' => 'font-size: 11px; padding: 3px 10px 3px 8px;'],
                'contentOptions' => ['style' => 'font-size: 11px; vertical-align: middle; padding: 3px 8px 3px 8px;'],
            ],
        ]
    ]
);
