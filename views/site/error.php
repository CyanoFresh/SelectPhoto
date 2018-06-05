<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="fullpage">
    <div class="fullpage-content">
        <div class="fullpage-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h1 class="fullpage-title"><?= $this->title ?></h1>
        <div class="fullpage-description">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
</div>
