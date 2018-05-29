<?php

namespace app\modules\admin\models\form;

use app\models\Link;
use app\models\Photo;
use app\modules\admin\components\ImageHelper;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class LinkUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'required'],
            [
                ['file'],
                'image',
                'skipOnEmpty' => false,
                'extensions' => 'jpg, png',
                'mimeTypes' => 'image/jpeg,image/png',
                'maxSize' => 10 * 1024 * 1024,
                'maxWidth' => YII_DEBUG ? null : 1800,
                'maxHeight' => YII_DEBUG ? null : 1500,
            ],
        ];
    }

    /**
     * @param Link $link
     * @return bool
     */
    public function upload($link)
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        $photoModel = new Photo();
        $photoModel->link_id = $link->id;

        if (!$photoModel->save()) {
            return false;
        }

        FileHelper::createDirectory($photoModel->link->getDirPath());

        $imageHelper = new ImageHelper();

        // Create thumbnail
        $imageHelper->thumbnail($this->file->tempName, $photoModel->getThumbnailPath());

        if ($link->watermark) {
            // Place watermark and save to the same temp path
            $imageHelper->watermark($this->file->tempName, $this->file->tempName);
        }

        if (!$this->file->saveAs($photoModel->getFilePath())) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return true;
    }
}
