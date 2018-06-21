<?php

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

use yii\widgets\ListView;

$this->title = 'Админпанель';
?>

<h1 class="page-header">
    Ссылки
    <?= \yii\helpers\Html::a('<i class="fas fa-plus"></i>', ['/admin/link/create'], ['class' => 'btn btn-success']) ?>
    <?= \yii\helpers\Html::a('<i class="fas fa-folder-open"></i><span class="hidden-xs"> Добавить проект</span>', ['/admin/project/create'], ['class' => 'btn btn-default']) ?>
</h1>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'summaryOptions' => ['class' => 'alert alert-info'],
    'layout' => "{summary}\n<div class='row links'>{items}</div>\n{pager}",
    'itemView' => '_link',
]) ?>
