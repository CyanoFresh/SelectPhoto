<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */
/* @var $form yii\widgets\ActiveForm */

use Ramsey\Uuid\Uuid;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->link = $model->isNewRecord ? Uuid::uuid4()->toString() : $model->link;
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'link')->textInput(['maxlength' => 36]) ?>
        </div>
    </div>

    <?= $form->field($model, 'project_id')->dropDownList([]) ?>

    <?= $form->field($model, 'allow_comment')->checkbox() ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
