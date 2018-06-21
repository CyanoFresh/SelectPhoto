<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/admin/admin.css',
    ];
    public $js = [
        'js/admin/admin.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
