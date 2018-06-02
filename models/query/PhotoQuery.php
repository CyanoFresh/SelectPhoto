<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Photo]].
 *
 * @see \app\models\Photo
 */
class PhotoQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function selected()
    {
        return $this->andWhere(['selected' => true]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Photo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Photo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
