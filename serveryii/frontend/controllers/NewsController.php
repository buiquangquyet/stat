<?php

namespace frontend\controllers;

use common\components\Cache;
use common\models\mysql\modeldb\NewsRewrite;
use Yii;

class NewsController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60*60,
                'variations' => [
                    \Yii::$app->language,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM post',
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new NewsRewrite();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $key = 'CACHE_VIEW_NEWS_'.$id;
        $rs = Cache::get($key);
        if(empty($rs)){
            $rs = $this->render('view', [
                'model' => $this->findModel($id),
            ]);
            Cache::set($key,$rs,60);
        }
        return $rs;
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NewsRewrite::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
