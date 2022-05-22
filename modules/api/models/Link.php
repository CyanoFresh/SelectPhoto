<?php

namespace app\modules\api\models;

use app\models\query\PhotoQuery;
use yii\behaviors\AttributeTypecastBehavior;
use yii\db\ActiveQuery;

class Link extends \app\models\Link
{
    public function fields()
    {
        return [
            'id',
            'active',
            'disable_after_submit',
            'link',
            'submitted',
            'allow_comment',
            'show_tutorial',
            'max_photos',
            'photos',
        ];
    }

    public function behaviors()
    {
        return [
            'typecast' => [
                'class' => AttributeTypecastBehavior::class,
                'typecastAfterFind' => true,
                // 'attributeTypes' will be composed automatically according to `rules()`
            ],
        ];
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
}
