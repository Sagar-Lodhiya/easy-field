<?php

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $sourcePath = 'theme/';

    public $css = [
        'css/bootstrap.min.css',
        'css/themes.css',
        'css/main.css',
        'css/plugins.css',
    ];
    public $js = [
        'js/vendor/modernizr-respond.min.js',
        'js/vendor/bootstrap.min.js',
        'js/app.js',
        'js/plugins.js',
        'js/pages/login.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
