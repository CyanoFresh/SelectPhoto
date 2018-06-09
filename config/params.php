<?php

use yii\helpers\ArrayHelper;

$params = [
    'fromEmail' => [
//        'robot@example.com' => 'SelectPhoto'
    ],
    'adminEmail' => 'admin@example.com',
    'users' => [],
];

return ArrayHelper::merge($params, require 'params-local.php');
