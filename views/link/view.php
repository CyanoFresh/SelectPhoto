<?php

/* @var $this yii\web\View */
/* @var $linkModel \app\models\Link */

/* @var $photosModels \app\models\Photo[] */

/**
 * TODO: translation
 */

use yii\helpers\Url;

\app\assets\LinkAsset::register($this);

$this->title = 'Выберите фото';

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
    'content' => 'Выберите' . ($linkModel->max_photos !== 0 ? ' ' . $linkModel->max_photos : '') . ' фото',
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
    'content' => 'Выберите наилучшие фото и нажмите кнопку ЗАВЕРШИТЬ, чтобы фотограф знал, когда вы закончите',
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
                    Выберите <?= $linkModel->max_photos ? $linkModel->max_photos : '' ?> фото
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
                        <li>Листайте фото кнопками влево или вправо.</li>
                        <li>
                            Нажимайте кнопку <a class="btn btn-xs btn-round btn-success">Выбрать</a> на понравившиеся
                            фото. Вы можете отменить выбор повторным нажатием.
                        </li>
                        <li>
                            Чтобы фотограф знал, когда вы закончите, нажмите
                            <a class="btn btn-xs btn-round btn-warning">Завершить</a>
                        </li>
                    </ol>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <p class="pull-left text-muted">
                    SelectPhoto by <a href="https://solomaha.com/" class="product-font" target="_blank">Alex
                        Solomaha</a>
                </p>
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
