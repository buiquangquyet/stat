<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 19/07/2018
 * Time: 14:33
 */

namespace frontend\controllers;

use yii\web\Controller;
class HomeController extends Controller
{

    public function actionIndex(){
        return $this->render('index');
    }

}