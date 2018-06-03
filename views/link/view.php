<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */

/* @var $photosModels \app\models\Photo[] */

use yii\helpers\Url;

\app\assets\LinkAsset::register($this);

$this->title = 'Выберите фото';

$photosArray = [];
$selectedCount = 0;

foreach ($photosModels as $photosModel) {
    $photosArray[] = [
        'src' => $photosModel->getFileUrl(),
        'thumb' => $photosModel->getThumbnailUrl(),
        'selected' => (bool)$photosModel->selected,
        'photo-id' => $photosModel->id,
        'comment' => $photosModel->comment,
    ];

    if ($photosModel->selected) {
        $selectedCount++;
    }
}

$this->registerJsVar('photos', $photosArray);
$this->registerJsVar('selectedPhotosCount', $selectedCount);
$this->registerJsVar('selectPhotoUrl', Url::to(['link/select-photo', 'link' => $linkModel->link]));
$this->registerJsVar('commentPhotoUrl', Url::to(['link/comment-photo', 'link' => $linkModel->link]));
$this->registerJsVar('submitLinkUrl', Url::to(['link/submit', 'link' => $linkModel->link]));
?>

<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Выберите фото</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li>Листайте фото кнопками влево и вправо.</li>
                    <li>Нажмайте кнопку <a class="btn btn-xs btn-success">Выбрать</a> на понравившиеся фото.</li>
                    <li>
                        Чтобы фотограф знал, когда вы закончите, нажмите <a class="btn btn-xs btn-primary">Завершить</a>
                    </li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-hide-forever" data-dismiss="modal">
                    больше не показывать
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</div>

<div id="gallery"></div>

<div class="fullpage">
    <div class="fullpage-content">
        <div class="fullpage-icon"><i class="fas fa-check"></i></div>
        <h1 class="fullpage-title">Отправлено. Можете закрыть вкладку</h1>
    </div>
</div>

<div class="fullpage loader">
    <div class="fullpage-content">
        <div class="fullpage-icon"><i class="fas fa-spinner-third fa-spin"></i></div>
    </div>
</div>
