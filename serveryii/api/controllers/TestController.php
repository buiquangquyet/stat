<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 15/10/2018
 * Time: 09:24
 */

namespace api\controllers;


use api\models\Cambridge;
use common\components\BlockChain\BlockChain;
use common\components\Cambridge\SkPublishAPI;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $bl = new BlockChain();
        $bl->addaBlock(1,time(), 'Data thứ 2');
        $bl->addaBlock(2,time(), 'Data thứ 3');
        $bl->addaBlock(3,time(), 'Data thứ 4');
        print_r($bl);

    }
}