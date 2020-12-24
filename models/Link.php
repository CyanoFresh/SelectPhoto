<?php

namespace app\models;

use app\models\query\LinkQuery;
use app\models\query\PhotoQuery;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property boolean $active
 * @property string $link
 * @property string $name
 * @property int $project_id
 * @property int $user_id
 * @property boolean $submitted
 * @property boolean $allow_comment
 * @property boolean $disable_after_submit
 * @property boolean $watermark
 * @property boolean $show_tutorial
 * @property boolean $disable_right_click
 * @property string $greeting_message
 * @property int $max_photos
 * @property int $created_at
 * @property int $submitted_at
 *
 * @property Project $project
 * @property Photo[] $photos
 * @property Photo[] $selectedPhotos
 * @property User $user
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['max_photos', 'created_at', 'submitted_at'], 'integer'],
            [
                [
                    'active',
                    'allow_comment',
                    'disable_after_submit',
                    'watermark',
                    'show_tutorial',
                    'disable_right_click',
                ],
                'boolean',
            ],
            [
                [
                    'active',
                    'allow_comment',
                    'disable_after_submit',
                    'watermark',
                    'show_tutorial',
                    'disable_right_click',
                ],
                'default',
                'value' => true,
            ],
            [['link', 'name'], 'required'],
            [['link'], 'default', 'value' => $this->generateLink()],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
            [['link'], 'string', 'max' => 36],
            [['link'], 'unique'],
            [['name'], 'string', 'max' => 255],
            [['greeting_message'], 'string'],
            [
                ['project_id'],
                'exist',
                'skipOnError' => false,
                'targetClass' => Project::class,
                'targetAttribute' => ['project_id' => 'id'],
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError' => false,
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'active' => Yii::t('app', 'Активна'),
            'link' => Yii::t('app', 'Ссылка'),
            'name' => Yii::t('app', 'Название'),
            'project_id' => Yii::t('app', 'Проект'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'submitted' => Yii::t('app', 'Завершено Пользователем'),
            'allow_comment' => Yii::t('app', 'Разрешить комментирование'),
            'disable_after_submit' => Yii::t('app', 'Отключить после завершения'),
            'watermark' => Yii::t('app', 'Ставить watermark'),
            'max_photos' => Yii::t('app', 'Макс. Фото'),
            'show_tutorial' => Yii::t('app', 'Показывать инструкцию'),
            'disable_right_click' => Yii::t('app', 'Отключить скачивание фото'),
            'greeting_message' => Yii::t('app', 'Сообщение Приветствия'),
            'created_at' => Yii::t('app', 'Дата Создания'),
            'submitted_at' => Yii::t('app', 'Дата Завершения'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id'])->inverseOf('links');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('links');
    }

    /**
     * @return ActiveQuery|PhotoQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::class, ['link_id' => 'id'])
            ->orderBy('sort_order')
            ->inverseOf('link');
    }

    /**
     * @return ActiveQuery|PhotoQuery
     */
    public function getSelectedPhotos()
    {
        return $this->getPhotos()->selected();
    }

    /**
     * {@inheritdoc}
     * @return LinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinkQuery(get_called_class());
    }

    /**
     * Remove all photos when deleted
     *
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();

        FileHelper::removeDirectory($this->getDirPath());
    }

    /**
     * @return string
     */
    public function getDirPath()
    {
        return Yii::getAlias('@webroot/uploads/' . $this->id);
    }

    /**
     * @return bool
     */
    public function submit()
    {
        $this->submitted = true;
        $this->submitted_at = time();

        if ($this->disable_after_submit) {
            $this->active = false;
        }

        $message = Yii::$app->mailer
            ->compose('checked', [
                'linkModel' => $this,
                'selectedPhotoModels' => $this->getPhotos()->selected()->all(),
            ])
            ->setFrom(Yii::$app->params['fromEmail'])
            ->setTo($this->user->email)
            ->setSubject(Yii::t('app', 'Выбор фото для "{name}" завершен', [
                'name' => $this->name,
            ]));

        $ok = $this->save() && $message->send();

        return $ok;
    }

    /**
     * Get first part of UUID v4
     *
     * @return string
     */
    public function generateLink()
    {
        return mb_substr(Uuid::uuid4()->toString(), 0, 8);
    }
}
