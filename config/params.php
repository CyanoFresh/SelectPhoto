<?php

use yii\helpers\ArrayHelper;

$params = [
    'fromEmail' => [
//        'robot@example.com' => 'SelectPhoto'
    ],
];

return ArrayHelper::merge($params, require 'params-local.php');
