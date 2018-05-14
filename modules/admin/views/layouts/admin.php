<?php

/** @var $this \yii\web\View */
/** @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

\app\modules\admin\assets\AdminAsset::register($this);
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>

<?php
NavBar::begin([
    'brandLabel' => 'SelectPhoto Admin',
    'brandUrl' => ['/admin/default/index'],
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

<div class="wrap">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= $content ?>
    </div>
</div>

<?php $this->endContent(); ?>
