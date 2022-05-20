<?php

namespace app\modules\api\controllers;

use app\modules\api\models\Link;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class LinkController extends Controller
{
//
//    public function behaviors()
//    {
//        return array_merge(parent::behaviors(), [
//
//            // For cross-domain AJAX request
//            'corsFilter' => [
//                'class' => \yii\filters\Cors::className(),
//                'cors' => [
//                    // restrict access to domains:
//                    'Origin' => ['*',],
//                    'Access-Control-Request-Method' => ['POST', 'GET',],
//                    'Access-Control-Max-Age' => 3600,                 // Cache (seconds)
//                ],
//            ],
//
//        ]);
//    }

    public function verbs()
    {
        return [
            'view' => ['GET'],
            'select' => ['POST'],
            'comment' => ['POST'],
            'submit' => ['POST'],
        ];
    }

    public function actionView($link)
    {
        $model = Link::find()->where(['link' => $link])->with('photos')->one();

        if (!$model) {
            throw new NotFoundHttpException('Link was not found');
        }

        return $model;
    }

    public function actionSelect($link, $id)
    {
        $linkModel = Link::findOne([
            'link' => $link,
        ]);

        if (!$linkModel) {
            throw new NotFoundHttpException('Link was not found');
        }

        if (!$linkModel->active) {
            throw new ForbiddenHttpException("Link is not active");
        }

        $photoModel = $linkModel->getPhotos()->where(['id' => $id])->one();

        if (!$photoModel) {
            throw new NotFoundHttpException('Photo was not found');
        }

        $photoModel->selected = !$photoModel->selected;

        if ($linkModel->max_photos !== 0) {
            $selectedCount = (int)$linkModel->getSelectedPhotos()->count();

            if ($photoModel->selected && $selectedCount > $linkModel->max_photos) {
                throw new ForbiddenHttpException("Already selected the max amount ($linkModel->max_photos)");
            }
        }

        if (!$photoModel->save()) {
            throw new ServerErrorHttpException('Cannot save photo');
        }

        return $photoModel;
    }

    public function actionComment($link, $id)
    {
        $linkModel = Link::findOne([
            'link' => $link,
        ]);

        if (!$linkModel) {
            throw new NotFoundHttpException('Link was not found');
        }

        if (!$linkModel->active) {
            throw new ForbiddenHttpException("Link is not active");
        }

        if (!$linkModel->allow_comment) {
            throw new ForbiddenHttpException("Comments are not allowed");
        }

        $photoModel = $linkModel->getPhotos()->where(['id' => $id])->one();

        if (!$photoModel) {
            throw new NotFoundHttpException('Photo was not found');
        }

        $photoModel->comment = \yii\helpers\HtmlPurifier::process(Yii::$app->request->post('comment'));

        if (!$photoModel->save()) {
            throw new ServerErrorHttpException('Cannot save comment');
        }

        return $photoModel;
    }

    public function actionSubmit($link)
    {
        $linkModel = Link::findOne([
            'link' => $link,
        ]);

        if (!$linkModel) {
            throw new NotFoundHttpException('Link was not found');
        }

        if (!$linkModel->active) {
            throw new ForbiddenHttpException("Link is not active");
        }

        if (!$linkModel->submit()) {
            throw new ServerErrorHttpException('Cannot submit');
        }

        return $link;
    }
}
