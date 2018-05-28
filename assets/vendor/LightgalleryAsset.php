<?php

namespace app\assets\vendor;

use yii\web\AssetBundle;

class LightgalleryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/lightgallery.css',
    ];
    public $js = [
        'js/lightgallery-all.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
