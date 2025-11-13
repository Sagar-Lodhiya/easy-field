<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DateRangeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'theme/css/daterangepicker.css',
    ];
    public $js = [
        'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
        'js/dateRange.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset', // Ensure Yii loads jQuery properly
    ];
}
