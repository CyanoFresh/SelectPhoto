<?php

namespace app\modules\admin\models\form;

use app\models\Photo;
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
     * @param int $linkId
     * @return bool
     */
    public function upload($linkId)
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        $photoModel = new Photo();
        $photoModel->link_id = $linkId;
        $photoModel->filename = $this->file->name;

        if (file_exists($photoModel->getFilePath())) {
            $photoModel->filename = $photoModel->filename . '_' . Uuid::uuid4()->toString();
        }

        if (!$photoModel->save()) {
            return false;
        }

        // TODO: watermark

        FileHelper::createDirectory($photoModel->link->getDirPath());

        if (!$this->file->saveAs($photoModel->getFilePath())) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return true;
    }
}
