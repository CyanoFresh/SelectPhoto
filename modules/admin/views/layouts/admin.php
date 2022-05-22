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
        ['label' => Yii::t('app', 'Адмінпанель'), 'url' => ['/admin/default/index']],
        ['label' => Yii::t('app', 'Проекти'), 'url' => ['/admin/project/index']],
        ['label' => Yii::t('app', 'Посилання'), 'url' => ['/admin/link/index']],
        [
            'label' => Yii::t('app', Yii::$app->user->identity->email),
            'items' => [
                [
                    'label' => Yii::t('app', 'Вийти'),
                    'url' => ['/admin/auth/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
            ],
        ],
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
                'label' => Yii::t('app', 'Адмінпанель'),
                'url' => ['/admin/default/index'],
            ],
        ]) ?>

        <?= $content ?>
    </div>
</div>

<?php $this->endContent(); ?>
