<?php

namespace app\assets\vendor;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle
{
    public $sourcePath = '@vendor/enyo/dropzone/dist/min';
    public $css = [
        'basic.min.css',
        'dropzone.min.css',
    ];
    public $js = [
        'dropzone.min.js',
    ];
}
