<?php

$params = require(__DIR__ . '/params.php');

return [
    'id' => 'yii2-web',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
            ],
        ],
        'view' => [
            'class' => \rmrevin\yii\minify\View::class,
            'minifyPath' => '@webroot/assets',
            'jsPosition' => [\yii\web\View::POS_END],
            'forceCharset' => 'UTF-8',
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'php:d.m.Y H:i',
        ],
        /*'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
        ],*/
    ],
    'params' => $params,
];
