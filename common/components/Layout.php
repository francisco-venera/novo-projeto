<?php

namespace common\components;

use yii\helpers\Html;
use yii\web\JsExpression;

class Layout {
    const LABEL_BTN_FILTER = 'Filtrar';
    const LABEL_BTN_NEW = 'Cadastrar';
    const LABEL_BTN_SUBMIT = 'Salvar';
    const LABEL_BTN_BACK = 'Voltar';
    const LABEL_BTN_CLOSE = 'Fechar';

    const CLASS_FULL_BTN_WITHOUT_COLOR = 'btn btn-sm btn-square btn-wide';

    const CLASS_BTN_DANGER = 'btn-danger';
    const CLASS_BTN_SUCCESS = 'btn-success';
    const CLASS_BTN_INFO = 'btn-info';
    const CLASS_BTN_DARK = 'btn-dark';
    const CLASS_BTN_PRIMARY = 'btn-primary';
    const CLASS_BTN_SECONDARY = 'btn-secondary';

    const ICON_BTN_FILTER = 'fa fa-filter';
    const ICON_BTN_NEW = 'fa fa-plus';
    const ICON_BTN_SUBMIT = 'fa fa-plus';
    const ICON_BTN_BACK = 'fa fa-arrow-left';
    const ICON_BTN_CLOSE = 'fa fa-times';

    const CHECKBOX_TEMPLATE = '<label class="kt-checkbox kt-checkbox--tick kt-checkbox--success">{input}{label}<span></span><label>{error}{hint}';

    const CHECKBOX_INLINE_TEMPLATE = '
                <label class="form-check form-check-inline kt-checkbox kt-checkbox--tick kt-checkbox--success">
                    {input}{label}
                    <span></span>
                </label>
                {error}{hint}';

    /**
     * Gerar o botão de submissão de formulários.
     *
     * @param string $text
     * @param string $title
     * @param array $options
     * @return string
     */
    public static function getButtonSubmit($text = self::LABEL_BTN_SUBMIT, $title = 'Salvar', $options = [])
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_FULL_BTN_WITHOUT_COLOR . ' ' .self::CLASS_BTN_PRIMARY,
                'title' => $title,
            ],
            $options
        );

        return Html::submitButton("{$text}", $options);
    }

    /**
     * Gerar o botão de filtro.
     *
     * @param string $text
     * @param string $icon
     * @param $options
     * @return string
     */
    public static function getButtonFilter($text = self::LABEL_BTN_FILTER, $icon = self::ICON_BTN_FILTER, $options = [])
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_BTN_DARK,
                'onClick' => new JsExpression('jQuery(".form-search").find("form").submit();'),
                'title' => 'Filtrar'
            ],
            $options
        );

        return self::getButton($text, null, $icon, $options);
    }

    /**
     * Gerar o botão de voltar.
     *
     * @param $url
     * @param string $text
     * @param array $options
     * @return string
     */
    public static function getButtonBack($url, $text = self::LABEL_BTN_BACK, $options = [])
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_BTN_SECONDARY,
                'title' => isset($options['title']) ? $options['title'] : $text
            ],
            $options
        );

        return self::getButton($text, $url, null, $options);
    }

    /**
     * Gerar o botão de fechar modais.
     *
     * @param string $text
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonClose($text = self::LABEL_BTN_CLOSE, $icon = self::ICON_BTN_CLOSE, $options = [])
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_BTN_SECONDARY,
                'data' => ['dismiss' => 'modal'],
                'title' => isset($options['title']) ? $options['title'] : $text
            ],
            $options
        );

        return self::getButton($text, null, $icon, $options);
    }

    /**
     * Gerar um botão de novo cadastro.
     *
     * @param $url
     * @param string $text
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonNew($url, $text = self::LABEL_BTN_NEW, $icon = self::ICON_BTN_NEW, $options = [])
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_BTN_SUCCESS,
                'title' => isset($options['title']) ? $options['title'] : $text
            ],
            $options
        );

        return self::getButton($text, $url, $icon, $options);
    }

    /**
     * Gerar um botão padrão.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonSecondary($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_SECONDARY);
    }

    /**
     * Gerar um botão com a classe danger.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonDanger($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_DANGER);
    }

    /**
     * Gerar um botão com a classe success.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonSuccess($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_SUCCESS);
    }

    /**
     * Gerar um botão com a classe success.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonDark($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_DARK);
    }

    /**
     * Gerar um botão com a classe info.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonInfo($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_INFO);
    }

    /**
     * Gerar um botão com a classe danger.
     *
     * @param string $text
     * @param string $url
     * @param string $icon
     * @param array $options
     * @return string
     */
    public static function getButtonPrimary($text, $url = null, $icon = null, $options = [])
    {
        $options = array_merge($options, ['title' => isset($options['title']) ? $options['title'] : $text]);

        return self::getButton($text, $url, $icon, $options, self::CLASS_BTN_PRIMARY);
    }

    /**
     * @param $text
     * @param null $url
     * @param null $icon
     * @param array $options
     * @param string $customClass
     * @return string
     */
    private static function getButton($text, $url = null, $icon = null, $options = [], $customClass = null)
    {
        $options = array_merge_recursive(
            [
                'class' => self::CLASS_FULL_BTN_WITHOUT_COLOR . " {$customClass}",
            ],
            $options
        );

        $i = Html::tag('i', null, ['class' => $icon]);
        return
            $url ?
                Html::a("{$i} {$text}", $url, $options) :
                Html::button("{$i} {$text}", $options);
    }

    /*
     * TABELAS
     */
    /**
     * Template do grid de listagem
     * @see: http://demos.krajee.com/grid#layout-templates
     */
    const GRID_TEMPLATE = '<div class="panel {type}">
                                {panelBefore}
                                {items}
                                {panelAfter}
                                {panelFooter}
                            </div>';

    /**
     * Template do campo {panelBefore}
     */
    const GRID_BEFORE_TEMPLATE = '<div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="btn-toolbar a kv-grid-toolbar pull-right" role="toolbar">{toolbar}</div>
                                    </div>
                                </div>';

    const GRID_BEFORE_TEMPLATE_NO_TOOLBAR = '';

    const GRID_AFTER_TEMPLATE = '{after}<div class="pull-right">{summary}</div>';

    /**
     * @param $model
     * @param string $template
     * @return string
     */
    public static function gridBeforeWithSearch($model, $template = '_search')
    {
        return \Yii::$app->getView()->render($template, ['model' => $model]).'
                <div class="btn-toolbar mb-4" role="toolbar">
                    {toolbar}
                </div>';
    }

    const GRID_STRIPED = false;
    const GRID_HOVER = true;
    const GRID_BORDERED = false;
    const GRID_CONDENSED = false;
    const GRID_PAJAX = false;
    const GRID_RESPONSIVE = true;
    const GRID_RESPONSIVE_WRAP = false;
}
