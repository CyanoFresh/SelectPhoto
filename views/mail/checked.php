<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
/* @var $linkModel \app\models\Link */
/* @var $selectedPhotoModels \app\models\Photo[] */

use yii\helpers\Url;
?>
Здравствуйте<br>
<br>
Пользователь завершил выбор фото по ссылке <b><a href="<?= Url::to(['/admin/link/view', 'id' => $linkModel->id], true) ?>"><?= $linkModel->name ?></a></b>.
<br>
<br>
Выбранные фото (<?= count($selectedPhotoModels) ?>):
<br>
<ul>
    <?php foreach ($selectedPhotoModels as $selectedPhotoModel): ?>
        <li>#<?= $selectedPhotoModel->id ?> - <?= $selectedPhotoModel->filename ?></li>
    <?php endforeach ?>
</ul>
