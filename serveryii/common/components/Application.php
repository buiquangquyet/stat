<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 2/10/2018
 * Time: 10:04 AM
 */

namespace common\components;


use Yii;

class Application extends \yii\web\Application
{
    public function handleRequest($request)
    {
        //check if connection is secure
        if (!$request->isSecureConnection) {
            //otherwise redirect to same url with https
            $secureUrl = str_replace('http', 'https', $request->absoluteUrl);
            //use 301 for a permanent redirect
            return Yii::$app->getResponse()->redirect($secureUrl, 301);
        } else {
            //if secure connection call parent implementation
            return parent::handleRequest($request);
        }
    }
}