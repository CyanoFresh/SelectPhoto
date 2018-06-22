<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */

/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Основное') ?></h3>

            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'link')->textInput(['maxlength' => 36]) ?>

            <?= $form->field($model, 'project_id')->dropDownList(\app\models\Project::getList(), [
                'prompt' => '--- Выберите проект ---'
            ]) ?>

            <?= $form->field($model, 'active')->checkbox() ?>

            <?= $form->field($model, 'watermark')->checkbox() ?>

            <?= $form->field($model, 'allow_comment')->checkbox() ?>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Параметры') ?></h3>

            <?= $form->field($model, 'max_photos')->input('number', [
                'min' => 0,
            ])->hint(Yii::t('app', 'Максимальное количество фото, которые можно выбрать. 0 - без ограничений')) ?>

            <?= $form->field($model, 'greeting_message')->textarea() ?>

            <?= $form->field($model, 'disable_after_submit')->checkbox() ?>

            <?= $form->field($model, 'disable_right_click')->checkbox() ?>

            <?= $form->field($model, 'show_tutorial')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Сохранить'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
