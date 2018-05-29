<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */

/* @var $photosModels \app\models\Photo[] */

use yii\helpers\Url;

\app\assets\LinkAsset::register($this);

$this->title = 'Выберите фото';

$photosArray = [];

foreach ($photosModels as $photosModel) {
    $photosArray[] = [
        'src' => $photosModel->getFileUrl(),
        'thumb' => $photosModel->getThumbnailUrl(),
        'selected' => (bool)$photosModel->selected,
        'photo-id' => $photosModel->id,
    ];
}

$this->registerJsVar('photos', $photosArray);
$this->registerJsVar('selectPhotoUrl', Url::to(['link/select-photo', 'link' => $linkModel->link]));
?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                    <li>Нажмайте кнопку <b>"Выбрать"</b> на понравившиеся фото.</li>
                    <li>Не забудьте нажать <b>"Завершить"</b>, когда закончите выбирать.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-hide-forever" data-dismiss="modal">
                    больше не показывать
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<div class="gallery"></div>
