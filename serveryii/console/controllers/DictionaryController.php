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
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;


class DictionaryController extends Controller
{
    public function actionIndex(){

        @date_default_timezone_set("Asia/Ho_Chi_Minh");
        $date = date('Y-m-d H:i:s');
        $date1 = str_replace('-', '/', $date);
        $after = date('Y-m-d H:i:s',strtotime($date1 . "-2 days"));
        $itemPush = Dictionary::find();
        $itemPush->where(['user_id'=>1]);
        $itemPush->andWhere(['>=','creat_time',$after]);
        $time = $itemPush->min('send_time');
        $itemPush = Dictionary::find();
        $itemPush->where(['user_id'=>1]);
        $itemPush->andWhere(['send_time'=>$time]);
        $itemPush= $itemPush->one();


        $itemPush1 = Dictionary::findOne($itemPush->id);
        $itemPush1->send_time = date('Y-m-d H:i:s');
        echo 'start creat_time: '.$itemPush1->creat_time.'  --  '.'send_time: '.$itemPush1->send_time.PHP_EOL;
        $itemPush1->save(false);
        echo 'finish creat_time: '.$itemPush1->creat_time.'  --  '.'send_time: '.$itemPush1->send_time.PHP_EOL;
        
        $quyet = [
            //'dsiu0-JdTUw:APA91bGbVzePtfoW5r3lkYc0n1UoZcVhGTzQi237URyUeqrjQzGpDi4e7ZGWo91lZByULoV0X1jMQWlTo67B144OT9TZ-HYjPcBqIXwtyl-L16XocSjFFd_XdkBVjOuHJCIAP_Src48-',
            //'ctxIFA3uS-k:APA91bE8eHpuC8ZKUSAcsem-kKvKXRaYzVBLh8PHRE3fyL0Rl4qO_x3zRpxKeEh4rmZwqmVxOSxAF9TZZbTNKT8cRRqVDDA3XsR5gM6_i7F4xvvBkSjgwncmp0CLxmXizTzUR0Mqu9lC',
            'eyAT9VAuYGc:APA91bFIlU4ifu-RDLVwOoezqCiaRKIc-RBv2qRRBxJ7RMgvWoBXX7NkFrQszSHyxOjaMIha4aS2j_vKW01fGA9Kp3BrkjnLQSPYcq3PR0vPb4ZcuhlymYbdlU_eMJMtZGmTP-az_TYi',
            'dRi1cg0QDbM:APA91bFtdbVhy7pv6csDA5F2oGskeOL4CSHIZJdZeKzNzCUKUOH1rTOAYObZwPtp4dHyKsps7NGS9rLMSxbsfVEiSad6TD68lhwIIAHp-M1vLORt6nEZDFMh-mJSR9S8-TVcfvt-F8fk',
        ];
        $tohanh = 'eWs2KuSvGYU:APA91bFMzwXi3THFYD4gkQ0YgV-Y59yGGbqL1t73RdYGY-zvebfTZp2Xi1t138Dwekvd_3nUpRNjcT0-HH3_M2emseqdHxO9T5sZk8atZP4UIJAR6HIQwZYPFijxwbIryGan7ADbYD3h';

        foreach ($quyet as $key=>$value){
            $header = [
                'Authorization'=>'key=AAAAMBCf86I:APA91bGgsx3CsPm0XiWszzMaGRuj4xuPtQHkN3iqykOFYEuSXXtH6JjM-69mvNEYC6lvdJk7TEkrSLkgHpDt6Gvx7dmfdEvjJW9G2xiI-80mnuPBddl0wW0eKCN_v1gW4TsSe7mDPqWj',
                'Content-Type'=>'application/json'
            ];

            echo 'creat_time: '.$itemPush->creat_time.'  --  '.'send_time: '.$itemPush->send_time.PHP_EOL;
            $image = 'http://'.Yii::$app->params['domain_api'].$itemPush->image;
            echo $image.PHP_EOL;
            $data = [
                'notification'=>[
                    "title"=> !empty($itemPush->word)?$itemPush->word:'',
                    "body"=> !empty($itemPush->mean)?$itemPush->mean:''.' -- '.!empty($itemPush->sentence)?$itemPush->sentence:'',
                    "icon"=> !empty($itemPush->image)?$image:'https://www.freeiconspng.com/uploads/no-image-icon-4.png',
                    "click_action"=> !empty($itemPush->link)?$itemPush->image:''
                ],
                'to'=>$value
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
            print_r($response->getData());
        }



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