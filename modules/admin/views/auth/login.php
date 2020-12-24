<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\modules\admin\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Авторизация');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">

    <div class="row">

        <div class="col-lg-offset-4 col-md-offset-3 col-md-6 col-lg-4">

            <h1><?= Yii::t('app', 'Авторизация') ?></h1>

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Войти'),
                    ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
