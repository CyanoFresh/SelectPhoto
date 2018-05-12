<?php

/** @var $this \yii\web\View */
/** @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$items = [
    ['label' => 'Админпанель', 'url' => ['/admin/default/index']],
    ['label' => 'Проекты', 'url' => ['/admin/project/index']],
    ['label' => 'Ссылки', 'url' => ['/admin/link/index']],
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $items,
]);

NavBar::end();
?>


<?= $content ?>

<?php $this->endContent(); ?>
