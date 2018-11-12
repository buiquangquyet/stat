<?php

namespace api\controllers;

use common\components\TextUtility;
use common\components\Util;
use Yii;
use common\models\mysql\modeldb\Course;
use common\models\mysql\modeldb\CourseRewrite;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends Controller
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
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourseRewrite();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Course model.
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
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Course();

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }

        $prefix = time();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->image->saveAs('uploads/course/' .$prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension);
                $name = $prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension;
                $model->image = 'http://apistat.beta.vn/uploads/course/'.$name;
                //Util::Thumbnail('http://apistat.beta.vn/'.$model->image, __DIR__."/../web/uploads/course/".$name);
                Util::Thumbnail($model->image, __DIR__."/../web/uploads/course/".$name,262,176);
                $model->save();
            }
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Course model.
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
                $model->image->saveAs('uploads/course/' .$prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension);
                $name = $prefix. TextUtility::alias($model->image->baseName) . '.' . $model->image->extension;
                $model->image = 'http://apistat.beta.vn/uploads/course/'.$name;
                Util::Thumbnail($model->image, __DIR__."/../web/uploads/course/".$name,262,176);
            }
            if($model->image ==''){
                $model->image = $old_image;
            }
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }


//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Course model.
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
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
