<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 27/11/2018
 * Time: 14:22
 */

namespace frontend\controllers;

use yii\rest\ActiveController;


class DictionaryController extends ActiveController
{
    public $modelClass = 'common\models\mysql\modeldb\Dictionary';


    public function fields()
    {
        return ['id'];
    }

    public function extraFields()
    {
        return ['sentence'];
    }

}