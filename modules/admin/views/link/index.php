<?php

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\search\LinkSearch */

/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Посилання');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-index">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-success']) ?>
    </h1>

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
            'submitted:boolean',

            ['class' => 'app\modules\admin\components\ActionButtonColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
