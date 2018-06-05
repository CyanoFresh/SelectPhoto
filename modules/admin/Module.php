<?php

namespace app\modules\admin;

use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->layout = 'admin';
    }
}
