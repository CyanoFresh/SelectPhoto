<?php

$local = require './db-local.php';

return array_merge($local, [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=APP_NAME',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
]);
