<?php

namespace app\modules\admin\components;

use yii\base\BaseObject;

class ImageHelper extends BaseObject
{
    public $thumbnailWidth = 100;
    public $thumbnailHeight = 80;
    public $thumbnailQuality = 85;

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
     * @param int|null $thumbnailWidth
     * @param int|null $thumbnailHeight
     * @param int|null $thumbnailQuality
     * @return bool
     */
    public function thumbnail($sourcePath, $savePath, $thumbnailWidth = null, $thumbnailHeight = null, $thumbnailQuality = null)
    {
        $thumbnailWidth = $thumbnailWidth ?? $this->thumbnailWidth;
        $thumbnailHeight = $thumbnailHeight ?? $this->thumbnailHeight;
        $thumbnailQuality = $thumbnailQuality ?? $this->thumbnailQuality;

        $sourceImage = imagecreatefromjpeg($sourcePath);

        if (!$sourceImage) {
            return false;
        }

        // GET ORIGINAL IMAGE DIMENSIONS
        list($sourceW, $sourceH) = getimagesize($sourcePath);

        // RESIZE IMAGE AND PRESERVE PROPORTIONS
        $thumb_w_resize = $thumbnailWidth;
        $thumb_h_resize = $thumbnailHeight;

        if ($sourceW > $sourceH) {
            $thumb_h_ratio = $thumbnailHeight / $sourceH;
            $thumb_w_resize = (int)round($sourceW * $thumb_h_ratio);
        } else {
            $thumb_w_ratio = $thumbnailWidth / $sourceW;
            $thumb_h_resize = (int)round($sourceH * $thumb_w_ratio);
        }

        if ($thumb_w_resize < $thumbnailWidth) {
            $thumb_h_ratio = $thumbnailWidth / $thumb_w_resize;
            $thumb_h_resize = (int)round($thumbnailHeight * $thumb_h_ratio);
            $thumb_w_resize = $thumbnailWidth;
        }

        // CREATE THE PROPORTIONAL IMAGE RESOURCE
        $thumbImage = imagecreatetruecolor($thumb_w_resize, $thumb_h_resize);

        if (!imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $thumb_w_resize, $thumb_h_resize, $sourceW,
            $sourceH)) {
            return false;
        }

        // CREATE THE CENTERED CROPPED IMAGE TO THE SPECIFIED DIMENSIONS
        $result = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        $thumb_w_offset = 0;
        $thumb_h_offset = 0;

        if ($thumbnailWidth < $thumb_w_resize) {
            $thumb_w_offset = (int)round(($thumb_w_resize - $thumbnailWidth) / 2);
        } else {
            $thumb_h_offset = (int)round(($thumb_h_resize - $thumbnailHeight) / 2);
        }

        if (!imagecopy($result, $thumbImage, 0, 0, $thumb_w_offset, $thumb_h_offset, $thumb_w_resize, $thumb_h_resize)) {
            return false;
        }

        return imagejpeg($result, $savePath, $thumbnailQuality);
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
