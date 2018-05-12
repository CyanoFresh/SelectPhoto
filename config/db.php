<?php

return \yii\helpers\ArrayHelper::merge([
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=selectphoto2',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
], require 'db-local.php');
