<?php

namespace app\modules\api\models;

use yii\behaviors\AttributeTypecastBehavior;

class Photo extends \app\models\Photo
{
    public function fields()
    {
        return [
            'id',
            'selected',
            'comment',
            'fileUrl',
            'thumbnailUrl',
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
}
