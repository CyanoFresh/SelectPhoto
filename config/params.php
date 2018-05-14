<?php

use yii\helpers\ArrayHelper;

$params = [
    'fromEmail' => [
        'robot@example.com' => 'SelectPhoto'
    ],
    'adminEmail' => 'admin@example.com',
];

return ArrayHelper::merge($params, require 'params-local.php');
