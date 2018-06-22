<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Проекты');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-index">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-success']) ?>
    </h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summaryOptions' => ['class' => 'alert alert-info'],
        'layout' => '{summary}<div class="table-responsive">{items}</div>{pager}',
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'active:boolean',
            'created_at:datetime',

            ['class' => 'app\modules\admin\components\ActionButtonColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
