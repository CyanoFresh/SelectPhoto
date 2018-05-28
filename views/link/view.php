<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */

/* @var $photosModels \app\models\Photo[] */

use yii\helpers\Url;

\app\assets\LinkAsset::register($this);

$this->title = 'Выберите фото';

$photosArray = [];

foreach ($photosModels as $photosModel) {
    $photosArray[$photosModel->id] = $photosModel->selected;
}

$this->registerJsVar('photos', $photosArray);
?>

<div class="alert alert-success">Выберите понравившиеся фото и нажмите <b>Завершить</b>.</div>

<div id="lightgallery">
    <?php foreach ($photosModels as $photosModel): ?>
        <a href="<?= Url::home() . 'uploads/' . $photosModel->link_id . '/' . $photosModel->filename ?>"
           title="<?= $photosModel->filename ?>">
            <img src="<?= Url::home() . 'uploads/' . $photosModel->link_id . '/' . $photosModel->filename ?>"
                 alt="<?= $photosModel->filename ?>"
                 title="<?= $photosModel->filename ?>"
                 data-selected="<?= $photosModel->selected ? 'true' : 'false' ?>"
                 data-id="<?= $photosModel->id ?>"
            >
        </a>
    <?php endforeach; ?>
</div>

<div class="text-center">
    <div class="btn btn-primary btn-lg btn-submit-link"
         href="<?= Url::to(['link/submit', 'link' => $linkModel->link]) ?>">Завершить выбор
    </div>
</div>
