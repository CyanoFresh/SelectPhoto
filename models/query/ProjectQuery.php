<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Project]].
 *
 * @see \app\models\Project
 */
class ProjectQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['active' => true]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Project[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Project|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
