<?php

/* @var $this yii\web\View */
/* @var $model app\models\Project */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $model->id],
            ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы действительно хотите удалить этот проект? Ссылки, принадлежащие этому проекту, останутся без изменений'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i> + ссылки', ['full-delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы действительно хотите удалить этот проект? Ссылки, принадлежащие этому проекту, будут удалены'),
                'method' => 'post',
            ],
        ]) ?>
    </h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'active:boolean',
            'description:ntext',
            'created_at:datetime',
        ],
    ]) ?>

</div>
