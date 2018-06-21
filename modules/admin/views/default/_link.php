<?php

/** @var $this \yii\web\View */

/** @var $model \app\models\Link */

use yii\helpers\Url;

$photo = $model->getPhotos()->limit(1)->one();
$photoUrl = false;

if ($photo) {
    $photoUrl = $photo->getFileUrl();
}
?>

<div class="col-md-3 col-lg-4">

    <a class="panel panel-default panel-link <?= $model->active ? '' : 'link-disabled' ?>"
       href="<?= Url::to(['/admin/link/view', 'id' => $model->id]) ?>"
    >
        <div class="panel-image" style="background-image: url(<?= $photoUrl ?>)"></div>

        <div class="panel-body">
            <div class="link-info">
                <div class="link-name">
                    <?= $model->name ?>
                    <?php if ($model->submitted): ?>
                        <div class="link-submitted" title="Завершено пользователем"><i class="far fa-check fa-fw"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="link-project">
                    <?= $model->project ? $model->project->name : 'Без проекта' ?>
                </div>
            </div>
            <div class="link-controls">
                <button class="btn btn-default" title="Копировать ссылку" onclick="copy('link-<?= $model->id ?>'); return false">
                    <i class="far fa-copy"></i>
                </button>
                <input type="text" value="<?= Url::to(['/link/view', 'link' => $model->link], true) ?>" id="link-<?= $model->id ?>" style="position: absolute;left: -9999px;" readonly>
            </div>
        </div>

    </a>
</div>
