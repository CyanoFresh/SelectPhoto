<?php

namespace app\models;

use app\models\query\LinkQuery;
use Yii;
use yii\helpers\FileHelper;

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
 * @property int $created_at
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
            [['active', 'project_id', 'allow_comment', 'created_at'], 'integer'],
            [['link'], 'required'],
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
            'name' => 'Имя',
            'project_id' => 'Проект',
            'submitted' => 'Завершено Пользователем',
            'allow_comment' => 'Разрешить комментирование',
            'created_at' => 'Дата Создания',
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

        FileHelper::removeDirectory('@webroot/uploads/' . $this->id);
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function submit()
    {
        $this->submitted = true;

        $transaction = Yii::$app->db->beginTransaction();

        $this->save();

        try {
            $mail = Yii::$app->mailer
                ->compose('checked', [
                    'linkModel' => $this,
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

            return false;
        }

        $transaction->commit();

        return true;
    }
}
