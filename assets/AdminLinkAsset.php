<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminLinkAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/admin/link.js',
    ];
    public $depends = [
        'app\assets\vendor\DropzoneAsset',
        'app\assets\AppAsset',
    ];
}
