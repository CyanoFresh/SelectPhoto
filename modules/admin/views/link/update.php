<?php

/* @var $this yii\web\View */
/* @var $model app\models\Link */

$this->title = Yii::t('app', 'Изменить Ссылку: {name}', ['name' => $model->name]);

$this->params['breadcrumbs'][] = ['label' => 'Ссылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактирование');
?>
<div class="link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
