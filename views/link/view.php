<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */

/* @var $photosModels \app\models\Photo[] */

/**
 * TODO: translation
 */

use yii\helpers\Url;

\app\assets\LinkAsset::register($this);

$this->title = 'Оберіть фото';

$photos = [];
$selectedCount = 0;

foreach ($photosModels as $photosModel) {
    $photos[] = [
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

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}

// Social optimization
$this->registerMetaTag([
    'name' => 'og:title',
    'content' => 'Оберіть' . ($linkModel->max_photos !== 0 ? ' ' . $linkModel->max_photos : '') . ' фото',
]);

$this->registerMetaTag([
    'name' => 'og:type',
    'content' => 'website',
]);

$this->registerMetaTag([
    'name' => 'og:url',
    'content' => Url::current([], true),
]);

$this->registerMetaTag([
    'name' => 'og:image',
    'content' => Url::home(true) . ($photosModels[0] ? $photosModels[0]->getFileUrl() : ''),
]);

$this->registerMetaTag([
    'name' => 'og:site_name',
    'content' => 'SelectPhoto',
]);

$this->registerMetaTag([
    'name' => 'og:description',
    'content' => 'Оберіть найкращі фото і натисніть кнопку ЗАВЕРШИТИ, щоб фотограф знав, коли ви завершите відбір',
]);

// Client variables
$this->registerJsVar('URL', [
    'selectPhoto' => Url::to(['link/select-photo', 'link' => $linkModel->link]),
    'commentPhoto' => Url::to(['link/comment-photo', 'link' => $linkModel->link]),
    'submitLink' => Url::to(['link/submit', 'link' => $linkModel->link]),
]);

$this->registerJsVar('LINK', [
    'options' => [
        'allowComment' => (bool)$linkModel->allow_comment,
        'maxPhotos' => $linkModel->max_photos,
        'greetingMessage' => $linkModel->greeting_message,
        'disableRightClick' => $linkModel->disable_right_click,
    ],
    'photos' => $photos,
    'selectedPhotosCount' => $selectedCount,
]);
?>

<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="helpModalLabel">
                    Оберіть <?= $linkModel->max_photos ? $linkModel->max_photos : '' ?> фото
                </h4>
            </div>
            <div class="modal-body">
                <?php if ($linkModel->greeting_message): ?>
                    <blockquote>
                        <p><?= $linkModel->greeting_message ?></p>
                    </blockquote>
                <?php endif; ?>

                <?php if ($linkModel->show_tutorial): ?>
                    <ol>
                        <li>Листайте фото кнопками вліво або вправо.</li>
                        <li>
                            Натискайте кнопку <a class="btn btn-xs btn-round btn-success">Обрати</a> на фото, яке сподобалось.
                            Ви можете відмінити вибір повторним натисканням.
                        </li>
                        <li>
                            Щоб фотограф знав, коли ви завершите, натисніть
                            <a class="btn btn-xs btn-round btn-warning">Завершити</a>
                        </li>
                    </ol>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-hide-forever" data-dismiss="modal">
                    більше не показувати
                </button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Закрити
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dialogModal" tabindex="-1" role="dialog" aria-labelledby="dialogModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="dialogModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Закрити
                </button>
            </div>
        </div>
    </div>
</div>

<div id="gallery"></div>

<div class="fullpage">
    <div class="fullpage-content">
        <div class="fullpage-icon"><i class="fas fa-check"></i></div>
        <h1 class="fullpage-title">Відправлено. Можете закрити вкладку</h1>
    </div>
</div>

<div class="fullpage loader">
    <div class="fullpage-content">
        <div class="fullpage-icon"><i class="fas fa-spinner-third fa-spin"></i></div>
    </div>
</div>
