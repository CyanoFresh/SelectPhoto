<?php

namespace app\models;

use app\models\query\PhotoQuery;
use Yii;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property int $selected
 * @property string $filename
 * @property int $link_id
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
            [['selected', 'link_id'], 'integer'],
            [['filename'], 'required'],
            [['comment'], 'string'],
            [['filename'], 'string', 'max' => 255],
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
            'filename' => 'Имя файла',
            'link_id' => 'Ссылка',
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
        return Yii::getAlias('@webroot/uploads/' . $this->link_id . '/' . $this->filename);
    }
}
