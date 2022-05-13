<?php

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var SignupForm $model */

use app\modules\admin\models\SignupForm;
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">

    <div class="row">

        <div class="col-lg-offset-4 col-md-offset-3 col-md-6 col-lg-4">
            <h1><?= Html::encode($this->title) ?></h1>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'type' => 'email']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <?= Html::a(Yii::t('app', 'Sign in'), ['/admin/auth/login']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
