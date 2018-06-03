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
        'js/lg/lightgallery.js',
        'js/lg/lg-thumbnail.js',
        'js/lg/lg-fullscreen.js',
//        'js/lg/lg-hash.js',
        'js/lg/lg-selectphoto.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
