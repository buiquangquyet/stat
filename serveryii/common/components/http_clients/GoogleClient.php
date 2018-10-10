<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-09-18
 * Time: 13:19
 */

namespace common\components\http_clients;

use Yii;
use yii\authclient\clients\Google;

class GoogleClient extends Google
{

    /**
     * Composes default [[returnUrl]] value.
     * @return string return URL.
     */
    protected function defaultReturnUrl()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        unset($params['code']);
        unset($params['state']);
        unset($params['scope']);
        $params[0] = Yii::$app->controller->getRoute();

        return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }
}