<?php

namespace app\modules\admin\components;

use app\models\Photo;
use DomainException;
use Yii;
use yii\base\BaseObject;

class PhotoSorter extends BaseObject
{
    /**
     * @var bool Validate photo ids and throw exceptions if not valid
     */
    public $validate = false;

    /**
     * @var int[]
     */
    public $validIDs;

    /**
     * @param int[] $photoIDs Array of Photo IDs ordered by user
     * @return bool
     */
    public function orderByIDs($photoIDs)
    {
        $sortOrder = 1;

        foreach ($photoIDs as &$photoID) {
            $photoID = (int)$photoID;

            if ($this->validate && !in_array($photoID, $this->validIDs)) {
                throw new DomainException(Yii::t('app', 'Не можна змінити це фото'));
            }

            Photo::updateAll(
                [
                    'sort_order' => $sortOrder,
                ],
                [
                    'id' => $photoID,
                ]
            );

            $sortOrder++;
        }

        return true;
    }
}
