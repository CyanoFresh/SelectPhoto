<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Link]].
 *
 * @see \app\models\Link
 */
class LinkQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(['active' => true]);
    }

    /**
     * @param string $link
     * @return $this
     */
    public function link($link)
    {
        return $this->andWhere(['link' => $link]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Link[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Link|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
