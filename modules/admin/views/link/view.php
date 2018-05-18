<?php

/* @var $this yii\web\View */

/* @var $model app\models\Link */

/* @var $uploadForm \app\modules\admin\models\form\LinkUploadForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

\app\assets\AdminLinkAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ссылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-view">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $model->id],
            ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'active:boolean',
            'link',
            'name',
            'project_id',
            'allow_comment:boolean',
            'disable_after_submit:boolean',
            'watermark:boolean',
            'created_at:datetime',
            'submitted:boolean',
            'submitted_at:datetime',
        ],
    ]) ?>

    <form action="<?= \yii\helpers\Url::to(['/admin/link/upload', 'id' => $model->id]) ?>" method="post"
          class="dropzone" enctype="multipart/form-data" id="dropzone">

        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>">
        <div class="fallback">
            <input name="LinkUploadForm[file]" type="file" multiple>
        </div>
    </form>

</div>
