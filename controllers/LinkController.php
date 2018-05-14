<?php

namespace app\controllers;

use app\models\Link;
use Props\NotFoundException;
use Yii;
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
                    'submit' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $savedLinkId = Yii::$app->session->get('link_id', false);

        if ($savedLinkId) {
            $savedLinkModel = Link::findOne($savedLinkId);

            if ($savedLinkModel && $savedLinkModel->active) {
                return $this->redirect(['link/view', 'link' => $savedLinkModel->link]);
            }
        }

        return $this->render('index');
    }

    /**
     * @param string $link
     * @return string
     * @throws NotFoundException
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

    public function actionSelectPhoto($id)
    {
        $requestedLink = Yii::$app->request->get('link');

        return 'TODO';
    }

    public function actionCommentPhoto($id)
    {
        $comment = Yii::$app->request->post('message');
        return 'TODO';
    }

    /**
     * @param string $link
     * @return mixed
     * @throws NotFoundException
     */
    public function actionSubmit($link)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        $link = Yii::$app->session->get('link', false);

        if ($link !== $link) {
            throw new NotFoundException('Ссылка не существует');
        }

        $linkModel = Link::find()->link($link)->active()->one();

        if (!$link || !$linkModel) {
            throw new NotFoundException('Ссылка не существует');
        }

        $result = $linkModel->submit();

        if (Yii::$app->request->isAjax) {
            return [
                'ok' => $result,
            ];
        } else {
            return $this->render('success', [
                'linkModel' => $linkModel,
            ]);
        }
    }

    /**
     * @param string $link
     * @return Link
     * @throws NotFoundException
     */
    protected function findLink($link)
    {
        $linkModel = Link::find()->active()->link($link)->one();

        if ($linkModel) {
            return $linkModel;
        }

        throw new NotFoundException('Ссылка не найдена');
    }
}
