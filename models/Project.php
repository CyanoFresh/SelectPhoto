<?php

namespace app\models;

use app\models\query\ProjectQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property int $user_id
 * @property int $active
 * @property string $name
 * @property string $description
 * @property int $created_at
 *
 * @property Link[] $links
 * @property User $user
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'created_at'], 'integer'],
            [['name'], 'required'],
            [['active'], 'default', 'value' => true],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'default', 'value' => Yii::$app->user->id],
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
            'user_id' => Yii::t('app', 'Пользователь'),
            'active' => Yii::t('app', 'Активен'),
            'name' => Yii::t('app', 'Название'),
            'description' => Yii::t('app', 'Описание'),
            'created_at' => Yii::t('app', 'Дата Создания'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(Link::class, ['project_id' => 'id'])->inverseOf('project');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('projects');
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
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return ArrayHelper::map(Yii::$app->user->identity->getProjects()->orderBy('name')->all(), 'id', 'name');
    }
}
