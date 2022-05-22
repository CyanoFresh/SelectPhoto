<?php

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = Yii::t('app', 'Создать Проект');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Проекти'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-create">

    <h1 class="page-header"><?= $this->title ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
