<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 2/2/2018
 * Time: 11:04 AM
 */

namespace common\components;


use common\lib\RedisQueue;
use common\models\model\Order;
use common\components\Util;
use Yii;
use yii\httpclient\Client;

class Logging
{

    const TYPE_ACTION_LOG = 'action';
    const TYPE_OPERATION_LOG = 'operation';
    const TYPE_PAYMENT_LOG = 'payment';
    const TYPE_OTHER_LOG = 'other';
    const TYPE_THIRD_PARTY_LOG = 'thirdparty';
    const TYPE_FRONTEND_LOG = 'Frontend';
    const PROVIDER_SC_BOXME = 'SC_BOXME';
    const PROVIDER_NGANLUONG = 'NL';
    const PROVIDER_NEXTMO = 'SMS_NEXTMO';
    const PROVIDER_SMS_VN = 'SMS_VN';

    public static function logOrder($typeLog, $bin, $user, $activity, $content, $soi = null)
    {
        $data = [];
        $data['action'] = $activity;
        $data['user'] = $user;
        $data['content'] = $content;
        $data['soi'] = $soi;
        $data['token'] = 'weshop@dmin';
        $url = Yii::$app->params['weshop_backend'] . '/log/bin/' . $bin . '/act/' . $typeLog . ($soi != null ? ('/soi/' . $soi) : '');

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();
        return $response;
    }



//
//    public static function logBIN($typeLog, $orderId, $user, $activity, $content)
//    {
//        $data = [];
//        $data['action'] = $activity;
//        $data['user'] = $user;
//        $data['content'] = $content;
//        $data['soi'] = "null";
//        $data['token'] = 'weshop@dmin';
//        $url = env('LOGGING_URL') . '/log/bin/' . $orderId . '/act/' . $typeLog . '/soi/null';
//        $response = Curl::to($url)
//            ->withData($data)
//            ->post();
//        return $response;
//    }
//
//    public static function logSOI($typeLog, $bin = null, $user, $activity, $content, $soi)
//    {
//        $data = [];
//        $data['action'] = $activity;
//        $data['user'] = $user;
//        $data['content'] = $content;
//        $data['soi'] = $soi;
//        $data['token'] = 'weshop@dmin';
//        if ($bin == null && $soi != null) {
//            $bin = OrderItem::whereId($soi)->pluck('orderId');
//            if ($bin != null) {
//                $bin = $bin[0];
//            }
//        }
//        $url = env('LOGGING_URL') . '/log/bin/' . $bin . '/act/' . $typeLog . ($soi != null ? ('/soi/' . $soi) : '');
//        $response = Curl::to($url)
//            ->withData($data)
//            ->post();
//        return $response;
//    }
//
    public static function logThirdParty($provider = 'nganluong', $user, $action, $request, $response)
    {
        $data = [];
        $data['user'] = $user;
        $data['action'] = $action;
        $data['request'] = $request;
        $data['response'] = $response;
        $data['date'] = date('Y-m-d');
        $url = Yii::$app->params['weshop_backend'] . '/log/logpayment/' . $provider . '/act/' . $action;

        $log = new Util();
        $response = $log->Curl_Post($url, $header = [], $data);
        return $response;
    }

    public static function logPayment($provider = 'nganluong', $user, $action, $request, $response)
    {
        $data = [];
        $data['user'] = $user;
        $data['action'] = $action;
        $data['request'] = $request;
        $data['response'] = $response;
        $data['date'] = date('Y-m-d');

        $url = Yii::$app->params['weshop_backend'] . '/log/logpayment/' . $provider . '/act/' . $action;
        //$log = new Util();
        //$response = $log->Curl_Post($url, $header = [], $data);


        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();
        return $response;
    }

    public static function LogProduct($datalog){
        $data = [];
        $data['sku'] = !empty($datalog['sku'])?$datalog['sku']:'';
        $data['alias'] = !empty($datalog['alias'])?$datalog['alias']:'';
        $data['action'] = !empty($datalog['action'])?$datalog['action']:'getItem';
        $data['provider'] = !empty($datalog['provider'])?$datalog['provider']:"AMAZON";
        $data['store_id'] = !empty($datalog['store_id'])?$datalog['store_id']:"1";
        $data['request'] = !empty($datalog['request'])?$datalog['request']:'';
        $data['status'] = !empty($datalog['status'])?$datalog['status']:'500';
        $data['andress'] = !empty($datalog['andress'])?$datalog['andress']:'';
        $data['created_at'] = !empty($datalog['created_at'])?$datalog['created_at']:date('Y-m-d H:i:s');
        $data['respone'] = !empty($datalog['respone'])?$datalog['respone']:'';

        $queue = new RedisQueue(Yii::$app->params['QUEUE_PRODUCT_DATA_LOG']);
        $count = $queue->count();
        if($count<=1000){
            $queue->push($data);
        }else{
            $queue->flush();
        }

        $url = Yii::$app->params['weshop_backend'] . '/log/logproduct';
//        $client = new Client();
//        $response = $client->createRequest()
//            ->setMethod('POST')
//            ->setUrl($url)
//            ->setData($data)
//            ->send();
//        return $response;
    }

    public static function SysViewDetail($datalog){
        $data = [];
        $data['sum'] = $datalog['sum'];
        $data['store_id'] = $datalog['store_id'];
        $data['date'] = $datalog['date'];
        $url = Yii::$app->params['weshop_backend'] . '/log/savedata';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();
        return $data;
    }


    public static function ecomobiLog($request,$response){
        $data['request'] = $request;
        $data['response'] = $response;
        $url = Yii::$app->params['weshop_backend'] . '/log/ecomobilog';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();
        return $response;
    }

    public static function apiLog($category,$message,$request,$response){
        $data['category'] = $category;
        $data['message'] = $message;
        $data['request'] = $request;
        $data['response'] = $response;
        $url = Yii::$app->params['weshop_backend'] . '/log/api-log';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($data)
            ->send();
        return $response;
    }
    static function callCurl($url, $data_string)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $response = curl_exec($ch);
        print_r($response);
        die;

        curl_close($ch);

        return $response;
    }
}