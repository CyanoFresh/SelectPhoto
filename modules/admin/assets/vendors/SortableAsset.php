<?php

namespace app\modules\admin\assets\vendors;

use yii\web\AssetBundle;

class SortableAsset extends AssetBundle
{
    public $sourcePath = '@npm/sortablejs';
    public $js = [
        'Sortable.min.js',
    ];
}
