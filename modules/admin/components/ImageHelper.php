<?php

namespace app\modules\admin\components;

use yii\base\BaseObject;

class ImageHelper extends BaseObject
{
    public $thumbnailWidth = 100;
    public $thumbnailHeight = 80;
    public $thumbnailQuality = 100;

    public $watermarkPath;

    public function init()
    {
        if (!$this->watermarkPath) {
            $this->watermarkPath = \Yii::getAlias('@webroot/uploads/watermark.png');
        }
    }

    /**
     * @param string $sourcePath
     * @param string $savePath
     * @return bool
     */
    public function thumbnail($sourcePath, $savePath)
    {
        $sourceImage = imagecreatefromjpeg($sourcePath);

        list($sourceWidth, $sourceHeight) = getimagesize($sourcePath);

        // from https://stackoverflow.com/questions/33708620/php-create-thumbnail-maintaining-aspect-ratio-remove-blank-portion

        // RESIZE IMAGE AND PRESERVE PROPORTIONS
        $thumb_w_resize = $this->thumbnailWidth;
        $thumb_h_resize = $this->thumbnailHeight;

        if ($sourceWidth > $sourceHeight) {
            $thumb_h_ratio = $this->thumbnailHeight / $sourceHeight;
            $thumb_w_resize = (int)round($sourceWidth * $thumb_h_ratio);
        } else {
            $thumb_w_ratio = $this->thumbnailWidth / $sourceWidth;
            $thumb_h_resize = (int)round($this->thumbnailHeight * $thumb_w_ratio);
        }

        if ($thumb_w_resize < $this->thumbnailWidth) {
            $thumb_h_ratio = $this->thumbnailWidth / $thumb_w_resize;
            $thumb_h_resize = (int)round($this->thumbnailHeight * $thumb_h_ratio);
            $thumb_w_resize = $this->thumbnailWidth;
        }

        // CREATE THE PROPORTIONAL IMAGE RESOURCE
        $thumbnailImage = imagecreatetruecolor($thumb_w_resize, $thumb_h_resize);

        if (!imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $thumb_w_resize, $thumb_h_resize,
            $sourceWidth,
            $sourceHeight)) {
            return false;
        }

        // CREATE THE CENTERED CROPPED IMAGE TO THE SPECIFIED DIMENSIONS
        $finalImage = imagecreatetruecolor($this->thumbnailWidth, $this->thumbnailHeight);

        $thumb_w_offset = 0;
        $thumb_h_offset = 0;

        if ($this->thumbnailWidth < $thumb_w_resize) {
            $thumb_w_offset = (int)round(($thumb_w_resize - $this->thumbnailWidth) / 2);
        } else {
            $thumb_h_offset = (int)round(($thumb_h_resize - $this->thumbnailHeight) / 2);
        }

        if (!imagecopy($finalImage, $thumbnailImage, 0, 0, $thumb_w_offset, $thumb_h_offset, $thumb_w_resize,
            $thumb_h_resize)) {
            return false;
        }

        return imagejpeg($finalImage, $savePath, $this->thumbnailQuality);
    }

    /**
     * @param string $sourcePath
     * @param string $savePath
     * @return bool
     */
    public function watermark($sourcePath, $savePath)
    {
        $watermarkImage = imagecreatefrompng($this->watermarkPath);
        $sourceImage = imagecreatefromjpeg($sourcePath);

        $watermarkWidth = imagesx($watermarkImage);
        $watermarkHeight = imagesy($watermarkImage);

        // Копирование изображения штампа на фотографию с помощью смещения края
        // и ширины фотографии для расчета позиционирования штампа.
        imagecopy($sourceImage, $watermarkImage, imagesx($sourceImage) - $watermarkWidth,
            imagesy($sourceImage) - $watermarkHeight, 0, 0,
            $watermarkWidth, $watermarkHeight);

        return imagejpeg($sourceImage, $savePath, 100);
    }
}
