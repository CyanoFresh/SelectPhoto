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
 * @property boolean $submitted
 * @property boolean $allow_comment
 * @property boolean $disable_after_submit
 * @property boolean $watermark
 * @property int $created_at
 * @property int $submitted_at
 *
 * @property Project $project
 * @property Photo[] $photos
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
            [['project_id', 'created_at', 'submitted_at'], 'integer'],
            [['active', 'allow_comment', 'disable_after_submit', 'watermark'], 'boolean'],
            [['active', 'allow_comment', 'disable_after_submit', 'watermark'], 'default', 'value' => true],
            [['link', 'name'], 'required'],
            [['link'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 255],
            [['link'], 'unique'],
            [
                ['project_id'],
                'exist',
                'skipOnError' => false,
                'targetClass' => Project::class,
                'targetAttribute' => ['project_id' => 'id']
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
            'active' => 'Активна',
            'link' => 'Ссылка',
            'name' => 'Название',
            'project_id' => 'Проект',
            'submitted' => 'Завершено Пользователем',
            'allow_comment' => 'Разрешить комментирование',
            'disable_after_submit' => 'Отключить после завершения',
            'watermark' => 'Ставить watermark',
            'created_at' => 'Дата Создания',
            'submitted_at' => 'Дата Завершения',
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
            ]
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
     * @return ActiveQuery|PhotoQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photo::class, ['link_id' => 'id'])->inverseOf('link');
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
     * @throws \yii\db\Exception
     */
    public function submit()
    {
        $this->submitted = true;
        $this->submitted_at = time();

        if ($this->disable_after_submit) {
            $this->active = false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        $this->save();

        try {
            $mail = Yii::$app->mailer
                ->compose('checked', [
                    'linkModel' => $this,
                    'selectedPhotoModels' => $this->getPhotos()->selected()->all(),
                ])
                ->setFrom(Yii::$app->params['fromEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->setSubject('Выбор фото для "' . $this->name . '" заврешен');

            if (!$mail->send()) {
                throw new \Exception('Не удалось отправить email');
            }
        } catch (\Exception $exception) {
            $transaction->rollBack();

            $this->submitted = false;
            $this->active = true;

            VarDumper::dump($exception);die;

            return false;
        }

        $transaction->commit();

        return true;
    }
}
