<?php

/** @var $this \yii\web\View */
/** @var $model \app\models\Link */

?>

<div class="col-md-3 col-lg-4">
    <a class="panel panel-default panel-link <?= $model->active ? 'link-active' : '' ?>" href="<?= \yii\helpers\Url::to(['/admin/link/view', 'id' => $model->id]) ?>">
        <div class="panel-body">
            <div class="link-name"><?= $model->name ?></div>
        </div>

        <?php if ($model->submitted): ?>
            <div class="link-submitted" title="Завершено пользователем"><i class="far fa-check"></i></div>
        <?php endif; ?>

        <?php if ($model->project): ?>
            <div class="link-project"><span class="label label-default"><?= $model->project->name ?></span></div>
        <?php endif; ?>
    </a>
</div>
