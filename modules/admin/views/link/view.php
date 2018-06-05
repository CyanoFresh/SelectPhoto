<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */

/* @var $uploadForm \app\modules\admin\models\form\LinkUploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

\app\assets\AdminLinkAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ссылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsVar('deletePhotoUrl', Url::to(['delete-photo']))
?>
<div class="link-view">

    <h2>
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
    </h2>

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

    <?php if ($model->submitted): ?>
        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="false" aria-controls="collapseOne">
                        <b>Выбранные фото <span class="badge"><?= (int)$model->getSelectedPhotos()->count() ?></span> <i
                                    class="fas fa-chevron-down"></i></b>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($model->photos as $photo): ?>
                            <div class="col-sm-3">
                                <i class="far fa-image"></i>
                                &nbsp;
                                #<?= $photo->id ?>
                                &nbsp;
                                <a href="<?= $photo->getFileUrl() ?>" target="_blank">
                                    <?= $photo->filename ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <h3>Фото</h3>

    <form action=" <?= \yii\helpers\Url::to(['/admin/link/upload', 'id' => $model->id]) ?>" method="post"
          class="dropzone" enctype="multipart/form-data" id="dropzone">

        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>">

        <div class="fallback">
            <input name="LinkUploadForm[file]" type="file" multiple>
        </div>

    </form>

    <hr>

    <div class="photos">
        <?php foreach ($model->photos as $photo): ?>
            <div class="photo <?= $photo->selected ? 'selected' : '' ?> <?= $photo->comment ? 'has-comment' : '' ?>"
                 data-id="<?= $photo->id ?>"
                 title="<?= $photo->comment ?>"
                 data-toggle="tooltip"
                 data-placement="top">

                <?php if ($photo->comment): ?>
                    <div class="comment-icon"><i class="fas fa-comment"></i></div>
                <?php endif; ?>

                <?= Html::a(
                    Html::img($photo->getThumbnailUrl(), [
                        'class' => 'img-responsive',
                        'alt' => '#' . $photo->id,
                    ]),
                    $photo->getFileUrl(),
                    [
                        'title' => '#' . $photo->id . ' - ' . $photo->filename,
                    ]
                ); ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
