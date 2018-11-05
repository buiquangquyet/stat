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




// filter out some fields, best used when you want to inherit the parent implementation
// and blacklist some sensitive fields.
//    public function fields()
//    {
//        $fields = parent::fields();
//
//        // remove fields that contain sensitive information
//        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
//
//        return $fields;
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
//                return $model->first_name . ' ' . $model->last_name;
//            },
//        ];
//    }

}