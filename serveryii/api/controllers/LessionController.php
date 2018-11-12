<?php

namespace api\controllers;

use common\components\TextUtility;
use common\components\Util;
use Yii;
use common\models\mysql\db\Lession;
use common\models\mysql\modeldb\LessionRewrite;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LessionController implements the CRUD actions for Lession model.
 */
class LessionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Lession models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LessionRewrite();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lession model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lession model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lession();

        $prefix = time();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->image->saveAs('uploads/lession/' .$prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension);
                $name = $prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension;
                $model->image = 'http://apistat.beta.vn/uploads/lession/'.$name;
                //Util::Thumbnail('http://apistat.beta.vn/'.$model->image, __DIR__."/../web/uploads/course/".$name);
                Util::Thumbnail($model->image, __DIR__."/../web/uploads/lession/".$name,262,176);
                $model->save();
            }
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lession model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $prefix = time();
        $old_image = $model->image;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->image->saveAs('uploads/lession/' .$prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension);
                $name = $prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension;
                $model->image = 'http://apistat.beta.vn/uploads/lession/'.$name;
                Util::Thumbnail($model->image, __DIR__."/../web/uploads/lession/".$name,262,176);
            }
            if($model->image ==''){
                $model->image = $old_image;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lession model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lession model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lession the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LessionRewrite::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
