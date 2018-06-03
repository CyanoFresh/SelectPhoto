<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */

/* @var $uploadForm \app\modules\admin\models\form\LinkUploadForm */

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
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </h1>

    <div class="row">
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'active:boolean',
                    [
                        'attribute' => 'link',
                        'value' => '<div class="input-group input-group-sm"><input type="text" value="' . \yii\helpers\Url::to([
                                '/link/view',
                                'link' => $model->link
                            ], true) . '" class="form-control" id="copyTarget" readonly>' .
                            '<span class="input-group-btn"><button title="Копировать" class="btn btn-primary" type="button" onclick="copy(\'copyTarget\')"><i class="far fa-copy"></i></button>    </span>' .
                            '</div>',
                        'format' => 'raw',
                    ],
                    'name',
                    'project_id',
                    'submitted:boolean',
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'allow_comment:boolean',
                    'disable_after_submit:boolean',
                    'watermark:boolean',
                    'created_at:datetime',
                    'submitted_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <h2>Фото</h2>

    <form action=" <?= \yii\helpers\Url::to(['/admin/link/upload', 'id' => $model->id]) ?>" method="post"
          class="dropzone" enctype="multipart/form-data" id="dropzone">

        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>">

        <div class="fallback">
            <input name="LinkUploadForm[file]" type="file" multiple>
        </div>

    </form>

    <hr>

    <div class="row">
        <?php foreach ($model->photos as $photo): ?>
            <div class="col-sm-2">
                <?= Html::a(
                    Html::img(Yii::getAlias($photo->getThumbnailUrl()), [
                        'class' => 'img-responsive',
                        'alt' => '#' . $photo->id,
                    ]),
                    Yii::getAlias($photo->getFileUrl()),
                    [
                        'title' => '#' . $photo->id,
                    ]
                ); ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
