<?php

namespace app\modules\admin\controllers;

use app\models\Photo;
use app\modules\admin\components\PhotoSorter;
use app\modules\admin\models\form\LinkUploadForm;
use Ramsey\Uuid\Uuid;
use Yii;
use app\models\Link;
use app\modules\admin\models\search\LinkSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'upload' => ['POST'],
                    'order-photos' => ['POST'],
                    'delete-photo' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Link model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $linkModel = $this->findModel($id);

        return $this->render('view', [
            'linkModel' => $linkModel,
        ]);
    }

    /**
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Link();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->link = $model->link ? $model->link : Uuid::uuid4()->toString();
        $model->active = $model->active ? $model->active : true;
        $model->allow_comment = $model->allow_comment ? $model->allow_comment : true;
        $model->disable_after_submit = $model->disable_after_submit ? $model->disable_after_submit : true;
        $model->watermark = $model->watermark ? $model->watermark : true;

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function actionUpload($id)
    {
        $link = $this->findModel($id);

        $uploadForm = new LinkUploadForm();
        $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');

        $ok = $uploadForm->upload($link);

        if (!$ok) {
            Yii::$app->response->statusCode = 400;
            return VarDumper::dumpAsString($uploadForm->errors);
        }

        return 'ok';
    }

    /**
     * @param int $id Link ID
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionOrderPhotos($id)
    {
        $link = $this->findModel($id);

        $requestPhotoIDs = Yii::$app->request->post('photoIDs');
        $linkPhotoIDs = $link->getPhotos()->select(['id'])->column();

        $photoSorter = new PhotoSorter([
            'validate' => true,
            'validIDs' => $linkPhotoIDs,
        ]);

        try {
            $ok = $photoSorter->orderByIDs($requestPhotoIDs);
        } catch (\DomainException $exception) {
            throw new ForbiddenHttpException($exception->getMessage());
        }

        return Json::encode(['ok' => $ok]);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionDeletePhoto($id)
    {
        $link = $this->findModel($id);

        $photoId = Yii::$app->request->post('id');

        $photoModel = Photo::findOne($photoId);

        if (!$photoModel or $link->id !== $photoModel->link_id) {
            throw new NotFoundHttpException('Фото не найдено');
        }

        $ok = $photoModel->delete() === 1;

        return Json::encode(['ok' => $ok]);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Ссылка не найдена');
    }
}
