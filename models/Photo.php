<?php

namespace app\models;

use app\models\query\PhotoQuery;
use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property boolean $selected
 * @property int $link_id
 * @property string $filename
 * @property string $comment
 * @property int $sort_order
 *
 * @property Link $link
 */
class Photo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link_id', 'filename', 'sort_order'], 'required'],
            [['link_id', 'sort_order'], 'integer'],
            [['selected'], 'boolean'],
            [['selected'], 'default', 'value' => false],
            [['comment', 'filename'], 'string'],
            [
                ['link_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Link::class,
                'targetAttribute' => ['link_id' => 'id']
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id'])->inverseOf('photos');
    }

    /**
     * {@inheritdoc}
     * @return PhotoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PhotoQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return Yii::getAlias('@webroot/uploads/' . $this->link_id . '/' . $this->id . '.jpg');
    }

    /**
     * @return string
     */
    public function getFileUrl()
    {
        return Yii::getAlias('@web/uploads/' . $this->link_id . '/' . $this->id . '.jpg');
    }

    /**
     * @param string $size (ex. '300x180')
     * @return string
     */
    public function getThumbnailPath($size = null)
    {
        $size = $size ?? '';

        return Yii::getAlias('@webroot/uploads/' . $this->link_id . '/' . $this->id . '_thumb' . $size . '.jpg');
    }

    /**
     * @param string $size (ex. '300x180')
     * @return string
     */
    public function getThumbnailUrl($size = null)
    {
        $size = $size ?? '';

        return Yii::getAlias('@web/uploads/' . $this->link_id . '/' . $this->id . '_thumb' . $size . '.jpg');
    }

    /**
     * Delete thumbnail and main image from FS if exist
     */
    public function afterDelete()
    {
        parent::afterDelete();

        if (file_exists($this->getFilePath())) {
            unlink($this->getFilePath());
        }

        if (file_exists($this->getThumbnailPath())) {
            unlink($this->getThumbnailPath());
        }
    }
}
