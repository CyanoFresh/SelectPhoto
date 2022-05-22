<?php

/* @var $this yii\web\View */
/* @var $model app\models\Project */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекти'), 'url' => ['index']];
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
                'confirm' => Yii::t('app', 'Ви дійсно хочете видалити цей проект? Посилання, що належали цьому проекту, залишаться без змін'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i> + посилання', ['full-delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Ви дійсно хочете видалити цей проект? Посилання, що належали цьому проекту, будуть видалені'),
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
