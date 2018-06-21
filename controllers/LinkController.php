<?php

namespace app\controllers;

use app\models\Link;
use app\models\Photo;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class LinkController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'selectPhoto' => ['POST'],
                    'commentPhoto' => ['POST'],
                    'submit' => ['POST'],
                    'view' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        if ($savedLinkModel = $this->getSavedLinkModel()) {
            return $this->redirect(['view', 'link' => $savedLinkModel->link]);
        }

        return $this->render('index');
    }

    /**
     * @param string $link
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($link)
    {
        $linkModel = $this->findLink($link);
        $photosModels = $linkModel->photos;

        Yii::$app->session->set('link_id', $linkModel->id);

        return $this->render('view', [
            'linkModel' => $linkModel,
            'photosModels' => $photosModels,
        ]);
    }

    /**
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionSelectPhoto($link)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        $savedLinkModel = $this->getSavedLinkModel();

        if (!$savedLinkModel or $link !== $savedLinkModel->link) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        $photoModel = Photo::findOne($id);

        if (!$photoModel || $photoModel->link_id !== $savedLinkModel->id) {
            throw new NotFoundHttpException('Фото не найдено');
        }

        $photoModel->selected = !((bool)$photoModel->selected);

        if ($photoModel->selected && $savedLinkModel->max_photos !== 0 && (int)$savedLinkModel->getSelectedPhotos()->count() >= $savedLinkModel->max_photos) {
            return [
                'ok' => false,
                'errors' => [
                    'Выбрано максимальное количество фото',
                ],
            ];
        }

        $ok = $photoModel->save();

        return [
            'ok' => $ok,
            'errors' => $photoModel->errors,
        ];
    }

    /**
     * @param string $link
     * @return mixed
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionCommentPhoto($link)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $comment = Yii::$app->request->post('text');
        $id = Yii::$app->request->post('id');

        if (!$comment or !$id) {
            throw new BadRequestHttpException('Сообщение отсутствует');
        }

        $savedLinkModel = $this->getSavedLinkModel();

        if (!$savedLinkModel or $link !== $savedLinkModel->link) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        if (!$savedLinkModel->allow_comment) {
            throw new UnauthorizedHttpException('Комментирование не разрешено');
        }

        $photoModel = Photo::findOne($id);

        if (!$photoModel || $photoModel->link_id !== $savedLinkModel->id) {
            throw new NotFoundHttpException('Фото не найдено');
        }

        $photoModel->comment = \yii\helpers\HtmlPurifier::process($comment);
        $ok = $photoModel->save();

        return [
            'ok' => $ok,
            'errors' => $photoModel->errors,
        ];
    }

    /**
     * @param string $link
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionSubmit($link)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $savedLinkModel = $this->getSavedLinkModel();

        if (!$savedLinkModel or $link !== $savedLinkModel->link) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        $linkModel = Link::find()->link($link)->active()->one();

        if (!$linkModel) {
            throw new NotFoundHttpException('Ссылка не найдена');
        }

        $ok = $linkModel->submit();

        return [
            'ok' => $ok,
        ];
    }

    /**
     * @param string $link
     * @return Link
     * @throws NotFoundHttpException
     */
    protected function findLink($link)
    {
        $linkModel = Link::find()->active()->link($link)->one();

        if ($linkModel) {
            return $linkModel;
        }

        throw new NotFoundHttpException('Ссылка не найдена');
    }

    /**
     * @return Link
     */
    protected function getSavedLinkModel()
    {
        $savedLinkId = Yii::$app->session->get('link_id', false);

        if ($savedLinkId) {
            return Link::find()->where(['id' => $savedLinkId])->active()->one();
        }

        return null;
    }
}
