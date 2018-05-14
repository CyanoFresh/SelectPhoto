<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */
/* @var $photosModels \app\models\Photo[] */

use yii\helpers\Url;

app\assets\vendor\PhotoSwipeAsset::register($this);

$this->title = 'Выберите фото';
?>

<div class="alert alert-info">Выберите понравившиеся фото и нажмите <b>Завершить</b>.</div>

<?php foreach ($photosModels as $photosModel): ?>
    <img src="<?= Url::home() . 'uploads/' . $photosModel->link_id . '/' . $photosModel->filename ?>"
         alt="Фото #<?= $photosModel->id ?>">
<?php endforeach; ?>

<div class="text-center">
    <div class="btn btn-primary btn-lg btn-submit-link" href="<?= Url::to(['link/submit', 'link' => $linkModel->link]) ?>">Завершить выбор</div>
</div>
