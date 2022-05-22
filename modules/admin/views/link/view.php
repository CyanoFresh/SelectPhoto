<?php

/* @var $this yii\web\View */
/* @var $linkModel app\models\Link */

/* @var $uploadForm \app\modules\admin\models\form\LinkUploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

\app\modules\admin\assets\AdminLinkAsset::register($this);

$this->title = $linkModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Посилання'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsVar('orderPhotosUrl', Url::to(['order-photos', 'id' => $linkModel->id]));
$this->registerJsVar('deletePhotoUrl', Url::to(['delete-photo', 'id' => $linkModel->id]));
?>
<div class="link-view">

    <h1 class="page-header">
        <?= $this->title ?>
        <?= Html::a('<i class="fas fa-copy"></i>', '#',
            [
                'class' => 'btn btn-success',
                'onclick' => "copy('copyTarget')",
                'title' => Yii::t('app', 'Копировать ссылку в буфер обмена')
            ]) ?>
        <?= Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id' => $linkModel->id],
            ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['delete', 'id' => $linkModel->id], [
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
                'model' => $linkModel,
                'attributes' => [
                    'active:boolean',
                    [
                        'attribute' => 'link',
                        'value' =>
                            '<div class="input-group input-group-sm">' .
                            '<input type="text" value="' .
                            Url::to('/', true) . $linkModel->link .
                            '" class="form-control" id="copyTarget" readonly>' .
                            '<span class="input-group-btn">' .
                            '<button title="' . Yii::t('app',
                                'Копировать ссылку в буфер обмена') . '" class="btn btn-primary" type="button" onclick="copy(\'copyTarget\')"><i class="far fa-copy"></i></button>' .
                            '</span>' .
                            '</div>',
                        'format' => 'raw',
                    ],
                    'name',
                    [
                        'attribute' => 'project_id',
                        'visible' => (bool)$linkModel->project,
                        'format' => 'raw',
                        'value' => Html::a($linkModel->project->name,
                            ['/admin/project/view', 'id' => $linkModel->project_id]),
                    ],
                    'watermark:boolean',
                    'submitted:boolean',
                    [
                        'attribute' => 'submitted_at',
                        'format' => 'datetime',
                        'visible' => (bool)$linkModel->submitted,
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= DetailView::widget([
                'model' => $linkModel,
                'attributes' => [
                    'allow_comment:boolean',
                    'disable_after_submit:boolean',
                    'show_tutorial:boolean',
                    'disable_right_click:boolean',
                    [
                        'attribute' => 'max_photos',
                        'format' => 'raw',
                        'value' => $linkModel->max_photos === 0 ? '∞' : $linkModel->max_photos,
                    ],
                    'greeting_message',
                    'created_at:datetime',
                ],
            ]) ?>
        </div>
    </div>

    <?php if ($linkModel->submitted): ?>
        <div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="false" aria-controls="collapseOne">
                        <b><?= Yii::t('app', 'Выбранные фото') ?> <span
                                    class="badge"><?= (int)$linkModel->getSelectedPhotos()->count() ?></span> <i
                                    class="fas fa-chevron-down"></i></b>
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <div class="row">
                        <?php foreach ($linkModel->selectedPhotos as $photo): ?>
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

    <div class="alert alert-info">
        Размер файла до 10 Мб, размер любой из сторон - до 2000px
    </div>

    <form action="<?= Url::to(['/admin/link/upload', 'id' => $linkModel->id]) ?>" method="post"
          class="dropzone" enctype="multipart/form-data" id="dropzone">

        <input id="form-token" type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->csrfToken ?>">

        <div class="fallback">
            <input name="LinkUploadForm[file]" type="file" multiple>
        </div>

    </form>

    <hr>

    <div class="row" id="photos">
        <?php foreach ($linkModel->photos as $photo): ?>
            <div class="col-lg-3 col-md-4 photo" data-id="<?= $photo->id ?>">
                <div class="panel panel-default"
                     style="background-image: url('<?= $photo->getThumbnailUrl('300x180') ?>')">
                    <div class="photo-props">
                        <?php if ($photo->selected): ?>
                            <a class="btn btn-round btn-sm btn-primary"><i class="fas fa-check"></i></a>
                        <?php endif; ?>

                        <?php if ($photo->comment): ?>
                            <a class="btn btn-round btn-sm btn-success"
                               title="#<?= $photo->id ?> - <?= $photo->filename ?>"
                               data-toggle="popover"
                               data-placement="top"
                               data-content="<?= $photo->comment ?>">
                                <i class="fas fa-comment"></i> <?= Yii::t('app', 'Коментар') ?>
                            </a>
                        <?php endif; ?>

                        <a href="#" class="btn btn-round btn-sm btn-danger btn-remove">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                    <div class="photo-filename"><?= $photo->filename ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
