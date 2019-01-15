<?php

namespace api\controllers;

use common\components\Util;
use Yii;
use common\models\mysql\db\Dictionary;
use common\models\mysql\modeldb\DictionaryRewrite;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DictionaryController implements the CRUD actions for Dictionary model.
 */
class DictionaryController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Dictionary models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!empty(Yii::$app->getUser()->getId())){
            $uer_id = Yii::$app->getUser()->getId();
        }
        $searchModel = new DictionaryRewrite();
        $searchModel->user_id = $uer_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Dictionary model.
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
     * Creates a new Dictionary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $model = new Dictionary();
        if(!empty(Yii::$app->getUser()->getId())){
            $uer_id = Yii::$app->getUser()->getId();
        }
        $prefix = time();


        if ($model->load(Yii::$app->request->post())) {
            $model->creat_time = date('Y-m-d H:i:s');
            $model->send_time = date('Y-m-d H:i:s');
            $model->user_id =$uer_id;
            $model->image = UploadedFile::getInstance($model, 'image');
            if ($model->image) {
                $model->image->saveAs('uploads/' .$prefix. $model->image->baseName . '.' . $model->image->extension);
                $name = $prefix. $model->image->baseName . '.' . $model->image->extension;
                $model->image = '/uploads/'.$name;
                Util::Thumbnail('http://apistat.beta.vn/'.$model->image, __DIR__."/../web/uploads/".$name);
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
     * Updates an existing Dictionary model.
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
                $model->image->saveAs('uploads/' .$prefix. $model->image->baseName . '.' . $model->image->extension);
                $name = $prefix. $model->image->baseName . '.' . $model->image->extension;
                $model->image = '/uploads/'.$name;
                Util::Thumbnail('http://apistat.beta.vn/'.$model->image, __DIR__."/../web/uploads/".$name);
            }
            if($model->image ==''){
                $model->image = $old_image;
            }
            $model->creat_time = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Dictionary model.
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
     * Finds the Dictionary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dictionary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dictionary::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
