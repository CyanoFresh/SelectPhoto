<?php

/** @var $this \yii\web\View */

/** @var $content string */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

\app\modules\admin\assets\AdminAsset::register($this);
?>

<?php $this->beginContent('@app/views/layouts/base.php'); ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'SelectPhoto<span>beta</span>',
        'brandOptions' => ['class' => 'brand product-font'],
        'brandUrl' => ['/admin/default/index'],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    $items = [
        ['label' => Yii::t('app', 'Админпанель'), 'url' => ['/admin/default/index']],
        ['label' => Yii::t('app', 'Проекты'), 'url' => ['/admin/project/index']],
        ['label' => Yii::t('app', 'Ссылки'), 'url' => ['/admin/link/index']],
        ['label' => Yii::t('app', 'Выйти'), 'url' => ['/admin/auth/logout'], 'linkOptions' => ['data-method' => 'post']],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $items,
    ]);

    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
            'homeLink' => [
                'label' => Yii::t('app', 'Админпанель'),
                'url' => ['/admin/default/index'],
            ],
        ]) ?>

        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p>By <a href="https://solomaha.com" class="product-font" target="_blank">Alex Solomaha</a></p>
    </div>
</footer>

<?php $this->endContent(); ?>
