<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 05/03/2018
 * Time: 11:13
 */
namespace api\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class UserController extends Controller
{
    public function actionIndex(){
        echo 123;
        die();
    }
}