<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */

$this->title = Yii::t('app', 'Створити Посилання');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Посилання'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h1 class="page-header"><?= $this->title ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
