<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var $form yii\widgets\ActiveForm */

use Ramsey\Uuid\Uuid;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'link')->textInput(['maxlength' => 36]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'project_id')->dropDownList([]) ?>
        </div>
    </div>

    <?= $form->field($model, 'active')->checkbox() ?>

    <?= $form->field($model, 'allow_comment')->checkbox() ?>

    <?= $form->field($model, 'disable_after_submit')->checkbox() ?>

    <?= $form->field($model, 'watermark')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
