<?php

namespace common\components\widgets;

use common\assets\DataTableAsset;
use common\components\Layout;
use kartik\grid\GridExportAsset;
use kartik\grid\GridFloatHeadAsset;
use kartik\grid\GridPerfectScrollbarAsset;
use kartik\grid\GridResizeColumnsAsset;
use kartik\grid\GridResizeStoreAsset;
use kartik\grid\GridViewAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class GridView extends \kartik\grid\GridView
{
    public $layout = <<< HTML
<div class="row kt-margin-b-15">
    <div class="col-sm-12 col-md-6">
        {summary}
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="dataTables_filter">
            {toolbar}
        </div>
    </div>
</div>
{items}
<div class="row">
    <div class="col-sm-12 col-md-6">
        {summary}
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="dataTables_paginate paging_full_numbers">
            {pager}
        </div>
    </div>
</div>
HTML;

    public $pager = [
        'disableCurrentPageButton' => true,
        'maxButtonCount' => 4,
        'linkOptions' => ['class' => 'page-link'],
        'linkContainerOptions' => ['class' => 'paginate_button page-item'],
        'nextPageLabel' => '<i class="la la-angle-right"></i>',
        'prevPageLabel' => '<i class="la la-angle-left"></i>',
        'firstPageLabel' => '<i class="la la-angle-double-left"></i>',
        'lastPageLabel' => '<i class="la la-angle-double-right"></i>',
        'prevPageCssClass' => 'previous',
        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
    ];

    public $export = [
        'options' => ['class' => 'btn btn-square btn-wide btn-sm btn-brand '],
        'label' => 'Download',
        'icon' => 'la la-cloud-download', //Html::tag('i', null, ''),
        'showConfirmAlert' => false,
    ];

    public $exportConfig = [
        \kartik\grid\GridView::CSV => ['label' => 'Save as CSV'],
        \kartik\grid\GridView::HTML => [],
        \kartik\grid\GridView::PDF => [],
    ];

    public $options = [
        'class' => 'dataTables_wrapper dt-bootstrap4 no-footer'
    ];

    public $summaryOptions = [
        'class' => 'dataTables_info'
    ];

    public $toolbar = [
        '{export}',
    ];

    /*
     * CONFIGURAÇÃO DA TABELA
     */
    public $bordered = Layout::GRID_BORDERED;
    public $condensed = Layout::GRID_CONDENSED;
    public $striped = Layout::GRID_STRIPED;
    public $responsive = Layout::GRID_RESPONSIVE;
    public $perfectScrollbar = false;
    public $persistResize = false;
    public $bootstrap = true;

    public $tableOptions = ['class' => 'table-sm'];

    /**
     * Registers client assets for the [[GridView]] widget.
     * @throws \Exception
     */
    protected function registerAssets()
    {
        /*
         * DESABILITADO DIALOG;
         * ADICIONADO DATATABLE ASSET (CSS).
         */
        DataTableAsset::register($this->getView());

        $view = $this->getView();
        $script = '';
        if ($this->bootstrap) {
            GridViewAsset::register($view);
        }
        /*
         * DESABILITADO - NÃO UTILIZAMOS.
         */
        // Dialog::widget($this->krajeeDialogSettings);
        $gridId = $this->options['id'];
        $NS = '.' . str_replace('-', '_', $gridId);
        if ($this->export !== false && is_array($this->export) && !empty($this->export)) {
            GridExportAsset::register($view);
            if (!isset($this->_module->downloadAction)) {
                $action = ["/{$this->moduleId}/export/download"];
            } else {
                $action = (array)$this->_module->downloadAction;
            }
            $gridOpts = Json::encode(
                [
                    'gridId' => $gridId,
                    'action' => Url::to($action),
                    'module' => $this->moduleId,
                    'encoding' => ArrayHelper::getValue($this->export, 'encoding', 'utf-8'),
                    'bom' => (int)ArrayHelper::getValue($this->export, 'bom', 1),
                    'target' => ArrayHelper::getValue($this->export, 'target', self::TARGET_BLANK),
                    'messages' => $this->export['messages'],
                    'exportConversions' => $this->exportConversions,
                    'skipExportElements' => $this->export['skipExportElements'],
                    'showConfirmAlert' => ArrayHelper::getValue($this->export, 'showConfirmAlert', true),
                ]
            );
            $gridOptsVar = 'kvGridExp_' . hash('crc32', $gridOpts);
            $view->registerJs("var {$gridOptsVar}={$gridOpts};");
            foreach ($this->exportConfig as $format => $setting) {
                $id = "jQuery('#{$gridId} .export-{$format}')";
                $genOpts = Json::encode(
                    [
                        'filename' => $setting['filename'],
                        'showHeader' => $setting['showHeader'],
                        'showPageSummary' => $setting['showPageSummary'],
                        'showFooter' => $setting['showFooter'],
                    ]
                );
                $genOptsVar = 'kvGridExp_' . hash('crc32', $genOpts);
                $view->registerJs("var {$genOptsVar}={$genOpts};");
                $expOpts = Json::encode(
                    [
                        'dialogLib' => ArrayHelper::getValue($this->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                        'gridOpts' => new JsExpression($gridOptsVar),
                        'genOpts' => new JsExpression($genOptsVar),
                        'alertMsg' => ArrayHelper::getValue($setting, 'alertMsg', false),
                        'config' => ArrayHelper::getValue($setting, 'config', []),
                    ]
                );
                $expOptsVar = 'kvGridExp_' . hash('crc32', $expOpts);
                $view->registerJs("var {$expOptsVar}={$expOpts};");
                $script .= "{$id}.gridexport({$expOptsVar});";
            }
        }
        $contId = '#' . $this->containerOptions['id'];
        $container = "jQuery('{$contId}')";
        if ($this->resizableColumns) {
            $rcDefaults = [];
            if ($this->persistResize) {
                GridResizeStoreAsset::register($view);
            } else {
                $rcDefaults = ['store' => null];
            }
            $rcOptions = Json::encode(array_replace_recursive($rcDefaults, $this->resizableColumnsOptions));
            GridResizeColumnsAsset::register($view);
            $script .= "{$container}.resizableColumns('destroy').resizableColumns({$rcOptions});";
        }
        if ($this->floatHeader) {
            GridFloatHeadAsset::register($view);
            // fix floating header for IE browser when using group grid functionality
            $skipCss = '.kv-grid-group-row,.kv-group-header,.kv-group-footer'; // skip these CSS for IE
            $js = 'function($table){return $table.find("tbody tr:not(' . $skipCss . '):visible:first>*");}';
            $opts = [
                'floatTableClass' => 'kv-table-float',
                'floatContainerClass' => 'kv-thead-float',
                'getSizingRow' => new JsExpression($js),
            ];
            if ($this->floatOverflowContainer) {
                $opts['scrollContainer'] = new JsExpression("function(){return {$container};}");
            }
            $this->floatHeaderOptions = array_replace_recursive($opts, $this->floatHeaderOptions);
            $opts = Json::encode($this->floatHeaderOptions);
            $script .= "jQuery('#{$gridId} .kv-grid-table:first').floatThead({$opts});";
            // integrate resizeableColumns with floatThead
            if ($this->resizableColumns) {
                $script .= "{$container}.off('{$NS}').on('column:resize{$NS}', function(e){" .
                    "jQuery('#{$gridId} .kv-grid-table:nth-child(2)').floatThead('reflow');" .
                    '});';
            }
        }
        $psVar = 'ps_' . Inflector::slug($this->containerOptions['id'], '_');
        if ($this->perfectScrollbar) {
            GridPerfectScrollbarAsset::register($view);
            $script .= "var {$psVar} = new PerfectScrollbar('{$contId}', " .
                Json::encode($this->perfectScrollbarOptions) . ');';
        }
        $this->genToggleDataScript();
        $script .= $this->_toggleScript;
        $this->_gridClientFunc = 'kvGridInit_' . hash('crc32', $script);
        $this->options['data-krajee-grid'] = $this->_gridClientFunc;
        $this->options['data-krajee-ps'] = $psVar;
        $view->registerJs("var {$this->_gridClientFunc}=function(){\n{$script}\n};\n{$this->_gridClientFunc}();");
    }
}
