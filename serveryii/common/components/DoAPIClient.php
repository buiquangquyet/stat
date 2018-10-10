<?php

namespace common\components;

use Yii;
use common\lib\Log4P;

class DoAPIClient
{

    /*
     * @param = [
     *      'binCode' => 7c152207283b,
     *      'isLogin => 0,
     *      'userId' => 0
     * ]
     */
    public static function getOrder($function, $params)
    {
        $submitURL = static::getApiUrl() . '/payment/order/' . $function . '.json';
        return self::call($submitURL, $params);
    }

    public static function createOrder($function, $params)
    {
        $submitURL = static::getApiUrl() . '/payment/order/' . $function . '.json';
        return self::call($submitURL, $params);
    }

    public static function paymentOrder($function, $params)
    {
        $submitURL = static::getApiUrl() . '/payment/order/' . $function . '.json';
        return self::call($submitURL, $params);
    }

    public static function updateOrder($function, $params)
    {
        $submitURL = static::getApiUrl() . '/payment/order/' . $function . '.json';
        return self::call($submitURL, $params);
    }

    public static function successOrder($function, $params)
    {
        $submitURL = static::getApiUrl() . '/payment/complete/' . $function . '.json';
        return self::call($submitURL, $params);
    }

    private static function call($submitURL, $params = [])
    {
        //$decode = json_decode(base64_decode($params), true);
        Log4P::debug("DoAPIClient call:");
        Log4P::debug("SubmitUrl: " . $submitURL);
//      Log4P::debug("Params: " . $decode);
//      Log4P::debug("Debug Params: " . var_export($decode));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $submitURL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        Log4P::debug("Result: " . json_encode($result));

        curl_close($ch);

        return self::jsonToObj($result);


    }

    private static function jsonToObj($xmlstr)
    {
        return json_decode($xmlstr);
    }

    private static function getApiUrl()
    {
        return Yii::$app->params['weshopApi'];
    }
}
