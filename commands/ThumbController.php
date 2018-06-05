<?php

namespace app\commands;

use yii\console\Controller;

class ThumbController extends Controller
{
    public function actionIndex($sourcePath = null, $thumbW = 100, $thumbH = 80)
    {
        $sourcePath = $sourcePath ?? \Yii::getAlias('@app/web/uploads/2/o.jpg');

        $sourceImage = imagecreatefromjpeg($sourcePath);

        if (!$sourceImage) {
            return false;
        }

        // GET ORIGINAL IMAGE DIMENSIONS
        list($sourceW, $sourceH) = getimagesize($sourcePath);

        // RESIZE IMAGE AND PRESERVE PROPORTIONS
        $thumb_w_resize = $thumbW;
        $thumb_h_resize = $thumbH;

        if ($sourceW > $sourceH) {
            $thumb_h_ratio = $thumbH / $sourceH;
            $thumb_w_resize = (int)round($sourceW * $thumb_h_ratio);
        } else {
            $thumb_w_ratio = $thumbW / $sourceW;
            $thumb_h_resize = (int)round($sourceH * $thumb_w_ratio);
        }

        if ($thumb_w_resize < $thumbW) {
            $thumb_h_ratio = $thumbW / $thumb_w_resize;
            $thumb_h_resize = (int)round($thumbH * $thumb_h_ratio);
            $thumb_w_resize = $thumbW;
        }

        // CREATE THE PROPORTIONAL IMAGE RESOURCE
        $thumbImage = imagecreatetruecolor($thumb_w_resize, $thumb_h_resize);

        if (!imagecopyresampled($thumbImage, $sourceImage, 0, 0, 0, 0, $thumb_w_resize, $thumb_h_resize, $sourceW,
            $sourceH)) {
            return false;
        }

        // ACTIVATE THIS TO STORE THE INTERMEDIATE IMAGE
        // imagejpeg($thumbImage, 'thumbs/temp_' . $thumb_w_resize . 'x' . $thumb_h_resize . '.jpg', 100);

        // CREATE THE CENTERED CROPPED IMAGE TO THE SPECIFIED DIMENSIONS
        $result = imagecreatetruecolor($thumbW, $thumbH);

        $thumb_w_offset = 0;
        $thumb_h_offset = 0;

        if ($thumbW < $thumb_w_resize) {
            $thumb_w_offset = (int)round(($thumb_w_resize - $thumbW) / 2);
        } else {
            $thumb_h_offset = (int)round(($thumb_h_resize - $thumbH) / 2);
        }

        if (!imagecopy($result, $thumbImage, 0, 0, $thumb_w_offset, $thumb_h_offset, $thumb_w_resize, $thumb_h_resize)) {
            return false;
        }

        // STORE THE FINAL IMAGE - WILL OVERWRITE $thumb_image_url
        if (!imagejpeg($result, \Yii::getAlias('@app/web/uploads/2/t2.jpg'), 80)) {
            return false;
        }

        return true;
    }
}
