<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 10/10/2018
 * Time: 17:20
 */

namespace console\controllers;
use common\components\Cache;
use common\models\mysql\db\Dictionary;
use yii\console\Controller;
use yii\httpclient\Client;


class DictionaryController extends Controller
{
    public function actionIndex(){

        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $itemPush = Dictionary::find();
        $itemPush->where(['user_id'=>1]);
        $time = $itemPush->min('send_time');
        $itemPush = Dictionary::find();
        $itemPush->where(['user_id'=>1]);
        $itemPush->andWhere(['send_time'=>$time]);
        $itemPush= $itemPush->one();
        $itemPush->send_time = date('Y-m-d H:i:s');
        $itemPush->save(false);

//        $key = 'DieLoop_'.$itemPush->word;
//        $flag = Cache::get($key);
//        if(!empty($flag)){
//            echo '$key:  '.$key.PHP_EOL;
//            exit();
//        }
//        Cache::set($key,1,60*2);

        $header = [
            'Authorization'=>'key=AAAAMBCf86I:APA91bGgsx3CsPm0XiWszzMaGRuj4xuPtQHkN3iqykOFYEuSXXtH6JjM-69mvNEYC6lvdJk7TEkrSLkgHpDt6Gvx7dmfdEvjJW9G2xiI-80mnuPBddl0wW0eKCN_v1gW4TsSe7mDPqWj',
            'Content-Type'=>'application/json'
        ];
        $tohanh = 'eWs2KuSvGYU:APA91bFMzwXi3THFYD4gkQ0YgV-Y59yGGbqL1t73RdYGY-zvebfTZp2Xi1t138Dwekvd_3nUpRNjcT0-HH3_M2emseqdHxO9T5sZk8atZP4UIJAR6HIQwZYPFijxwbIryGan7ADbYD3h';

        $data = [
            'notification'=>[
                "title"=> $itemPush->word." -- ".$itemPush->pronunciation,
                "body"=> $itemPush->mean.' -- '.$itemPush->sentence,
                "icon"=> $itemPush->image,
                "click_action"=> !empty($itemPush->link)?$itemPush->link:''
            ],
            'to'=>'ctxIFA3uS-k:APA91bE8eHpuC8ZKUSAcsem-kKvKXRaYzVBLh8PHRE3fyL0Rl4qO_x3zRpxKeEh4rmZwqmVxOSxAF9TZZbTNKT8cRRqVDDA3XsR5gM6_i7F4xvvBkSjgwncmp0CLxmXizTzUR0Mqu9lC'
        ];



        $url = 'https://fcm.googleapis.com/fcm/send';

        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl($url)
            ->setHeaders($header)
            ->setData($data)
            ->send();


        $itemPush = Dictionary::find();
        $itemPush->where(['user_id'=>2]);
        $time = $itemPush->min('send_time');
        $itemPush = Dictionary::find();
        $itemPush->where(['send_time'=>$time]);
        $itemPush->andWhere(['user_id'=>2]);
        $itemPush= $itemPush->one();
        $itemPush->send_time = date('Y-m-d H:i:s');
        $itemPush->save(false);



        if(!empty($itemPush)){
            echo $itemPush->word.PHP_EOL;
            $data = [
                'notification'=>[
                    "title"=> $itemPush->word." -- ".$itemPush->pronunciation,
                    "body"=> $itemPush->mean.' -- '.$itemPush->sentence,
                    "icon"=> $itemPush->image,
                    "click_action"=> !empty($itemPush->link)?$itemPush->link:''
                ],
                'to'=>$tohanh
            ];
            $url = 'https://fcm.googleapis.com/fcm/send';

            $client = new Client();
            $response = $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setMethod('POST')
                ->setUrl($url)
                ->setHeaders($header)
                ->setData($data)
                ->send();
        }



        print_r($response->getData());

    }
}