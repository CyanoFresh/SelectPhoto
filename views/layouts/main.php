<?php

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\FrontendAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>

<?= $content ?>

<?php $this->endContent(); ?>
