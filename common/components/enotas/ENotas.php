<?php

namespace common\components\enotas;

use Yii;

require(Yii::getAlias('@app') . '/../vendor/enotas/php-client/src/eNotasGW.php');

/**
 * Centralizar a importação da class eNotasGW
 *
 * Class ENotas
 * @package common\components\enotas
 */
class ENotas extends \eNotasGW
{

}
