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
use yii\rest\ActiveController;

/**
 * Site controller
 */
class UserController extends ActiveController
{






//    public function extraFields()
//    {
//        return ['profile'];
//    }

    public $modelClass = 'common\models\mysql\db\User';

//    public function fields()
//    {
//        return [
//            // field name is the same as the attribute name
//            'id',
//            // field name is "email", the corresponding attribute name is "email_address"
//            'email' => 'email_address',
//            // field name is "name", its value is defined by a PHP callback
//            'name' => function ($model) {
//                return $model->id . ' ' . $model->email;
//            },
//        ];
//    }

}