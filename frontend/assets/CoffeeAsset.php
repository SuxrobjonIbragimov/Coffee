<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class CoffeeAsset extends AssetBundle
{
    public $basePath = '@webroot/coffee';
    public $baseUrl = '@web/coffee';
    public $css = [
        'css/styles.css',
    ];
    public $js = [
        'js/mixitup.min.js',
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
