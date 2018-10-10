<?php
/**
 * Created by PhpStorm.
 * User: quyetbq
 * Date: 8/01/18
 * Time: 10:21 AM
 */

namespace common\components;


use common\models\model\Store;
use DOMDocument;
//use linslin\yii2\curl\Curl;
use yii\base\InvalidConfigException;
use yii\web\Request;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;
use linslin\yii2\curl;

class Util
{

    function Curl_Get($url, $header = [], $param = [])
    {
        $curl = new curl\Curl();
        if (!empty($header)) {
            $curl->setHeaders($header);
        }
        if (!empty($param)) {
            $curl->setGetParams($param);
        }
        $response = $curl->get($url);
        return $response;
    }

    function Curl_Post($url, $header = [], $param = [],$time=null)
    {
        $curl = new curl\Curl();
        if (!empty($header)) {
            $curl->setHeaders($header);
        }
        if (!empty($param)) {
            $curl->setPostParams($param);
        }
        if(!empty($time)){
            $curl->setOption(13, $time);
        }


        $response = $curl->post($url);
        return $response;
    }

}