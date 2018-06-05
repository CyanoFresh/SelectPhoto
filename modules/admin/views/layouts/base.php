<?php

/** @var $this \yii\web\View */

/** @var $content string */

\app\modules\admin\assets\AdminAsset::register($this);
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>

<?= $content ?>

<footer class="footer">
    <div class="container">
        <p>Made by <a href="https://solomaha.com" target="_blank">Alex Solomaha</a></p>
    </div>
</footer>

<?php $this->endContent(); ?>
