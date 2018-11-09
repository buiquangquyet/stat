<?php

namespace frontend\controllers;

use common\models\LoginForm;
use Yii;

class AuthController extends \yii\web\Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack('/home');
        } else {
            $model->password = '';
            print_r(Yii::$app->request->post());
            var_dump($model->login());
            die();
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        return $this->render('logout');
    }

}
