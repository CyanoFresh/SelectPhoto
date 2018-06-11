<?php

namespace app\commands;

use app\models\Photo;
use app\modules\admin\components\ImageHelper;
use yii\console\Controller;

class PhotoController extends Controller
{
    public function actionIndex($showAll = false)
    {
        $imageHelper = new ImageHelper();
        $photos = Photo::find();
        $count = 0;
        $failed = 0;

        echo "Migrating...\n\n";

        foreach ($photos->each() as $photo) {
            /** @var $photo Photo */
            if (file_exists($photo->getThumbnailPath('300x180'))) {
                if ($showAll) {
                    echo '#' . $photo->id . "    Exists\n";
                }

                continue;
            }

            // Create admin thumbnail
            $ok = $imageHelper->thumbnail($photo->getFilePath(), $photo->getThumbnailPath('300x180'), 300, 180, 95);

            if ($ok) {
                echo '#' . $photo->id . "    Success\n";
                $count++;
            } else {
                echo '#' . $photo->id . "    Failed\n";
                $failed++;
            }
        }

        echo "Done $count items; Failed $failed\n";
    }
}
