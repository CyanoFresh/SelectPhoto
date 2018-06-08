<?php

namespace app\assets;

use yii\web\AssetBundle;

class LinkAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/site.css',
        'https://fonts.googleapis.com/css?family=Product+Sans',
        'css/link.css',
    ];
    public $js = [
        'js/link.js',
    ];
    public $depends = [
        'app\assets\vendor\LightgalleryAsset',
        'app\assets\AppAsset',
    ];
}
