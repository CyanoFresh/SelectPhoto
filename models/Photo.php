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
            [['link_id', 'filename'], 'required'],
            [['link_id'], 'integer'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'selected' => 'Выбрано',
            'link_id' => 'Ссылка',
            'filename' => 'Имя файла',
            'comment' => 'Комментарий',
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
     * @return string
     */
    public function getThumbnailPath()
    {
        return Yii::getAlias('@webroot/uploads/' . $this->link_id . '/' . $this->id . '_thumb.jpg');
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return Yii::getAlias('@web/uploads/' . $this->link_id . '/' . $this->id . '_thumb.jpg');
    }
}
