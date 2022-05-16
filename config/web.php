<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'APP_NAME-web',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'defaultRoute' => 'link/index',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/views/mail',
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'link/index',
                'l/<link>' => 'link/view',
                'l/<link:\w+>/<id:\d+>' => 'link/select_photo',
                'api/link/<link>' => 'api/link/view',
                'api/link/<link>/submit' => 'api/link/submit',
                'api/link/<link>/photo/<id>/select' => 'api/link/select',
                'api/link/<link>/photo/<id>/comment' => 'api/link/comment',
            ],
        ],
        'view' => [
            'class' => \rmrevin\yii\minify\View::class,
            'minifyPath' => '@webroot/assets',
            'jsPosition' => [\yii\web\View::POS_END],
            'forceCharset' => 'UTF-8',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
//                    'js' => [],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/i18n',
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],
];
