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
class RouteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/route.css',
    ];
    public $js = [
        'https://maps.googleapis.com/maps/api/js?region=JP&key=AIzaSyAS1kzHr_O2Qg6cac3qn2X8WhdqhADAiRc&libraries=places',
        'https://unpkg.com/leaflet@1.9.3/dist/leaflet.js',
        'js/route.js',
    ];
}
