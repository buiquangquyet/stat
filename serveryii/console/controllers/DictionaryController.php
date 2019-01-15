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
use common\models\mysql\modeldb\User;
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;


class DictionaryController extends Controller
{
    public function actionIndex(){
        $header = [
            'Authorization'=>'key=AAAAMBCf86I:APA91bGgsx3CsPm0XiWszzMaGRuj4xuPtQHkN3iqykOFYEuSXXtH6JjM-69mvNEYC6lvdJk7TEkrSLkgHpDt6Gvx7dmfdEvjJW9G2xiI-80mnuPBddl0wW0eKCN_v1gW4TsSe7mDPqWj',
            'Content-Type'=>'application/json'
        ];
        $listToken['eyAT9VAuYGc:APA91bFIlU4ifu-RDLVwOoezqCiaRKIc-RBv2qRRBxJ7RMgvWoBXX7NkFrQszSHyxOjaMIha4aS2j_vKW01fGA9Kp3BrkjnLQSPYcq3PR0vPb4ZcuhlymYbdlU_eMJMtZGmTP-az_TYi']=1;// quyet
        $listToken['dRi1cg0QDbM:APA91bFtdbVhy7pv6csDA5F2oGskeOL4CSHIZJdZeKzNzCUKUOH1rTOAYObZwPtp4dHyKsps7NGS9rLMSxbsfVEiSad6TD68lhwIIAHp-M1vLORt6nEZDFMh-mJSR9S8-TVcfvt-F8fk']=1;// quyet
        $listToken['dIfe1Np11mo:APA91bFBZ0qNrR4dBPsaRR5FgoZNhbo8FUuJGSbWn3k1lJoN5qPXSgbhqkAQfRLtDOvoHK-ER_2hHHc8FhNh0Fvux7wK0-hlEulKBO474bg8WcnvkcdHtu-bMwgPq0wg77B5_JkhKTcn']=1;// quyet
        //$listToken['f3ls8x77wEA:APA91bEFbntzvGu40I98WS8KUFvCAm0IXfAk7v7dyL70yurppKXhAcSgs9zWf0s2W_2b3pHwqHHRjfkSa7M_n7qD272p-p6RHQIucmwfuAkGZsxCIcCRFc55uz9fiyQruVcHuHIVm_mX']=1;// quyet
        $listToken['eWs2KuSvGYU:APA91bFMzwXi3THFYD4gkQ0YgV-Y59yGGbqL1t73RdYGY-zvebfTZp2Xi1t138Dwekvd_3nUpRNjcT0-HH3_M2emseqdHxO9T5sZk8atZP4UIJAR6HIQwZYPFijxwbIryGan7ADbYD3h']=2;// hanh
        $listToken['fkDn0cpBSWA:APA91bHsXVgX3bvr429D7uABqNw4ndKvpAItnxHHL78p2Rdw6c9SaFkFnQPgxVC0aegcemKt09YnZ9VHEMB3yadVQPBhDnstKQcl3IAYloZn6zviOvQj9cxWCb7QM28EZIRFB-omAveR']=3;// thuy
        $listToken['c58Me2mi0LI:APA91bHOOKVqKeJwF0Pf4DAjSI4f8nph9t95Sap-_KW7R2xUf4uHCr6JypGC-w8SmDXTfInMHwdSVQm8CALoFbh3k9kwfZ71alDJou82NOb9foBrrWnjRy33jq8TpPnqaiRIsRonACmE-omAveR']=3;// thuy
        $listToken['cL7n3RcrOmw:APA91bGGt51RHfSKyljzFp87nvjEe8vNiixlX1hqoT1beKZ4uf9rc1B307nCC7gn_nuMO8S-_lozXD80V94b8pMNiFWibPQkMruYzEXN7ONw2pNLtepaIpY4H0ZUEDhKuPNMjpZtOrSH']=3;// thuy


        foreach ($listToken as $value=>$key){
            @date_default_timezone_set("Asia/Ho_Chi_Minh");
            $userName = User::GetNameById($key);


            $date = date('Y-m-d H:i:s');
            $date1 = str_replace('-', '/', $date);
            $after = date('Y-m-d H:i:s',strtotime($date1 . "-200 days"));
            $itemPush = Dictionary::find();
            $itemPush->where(['user_id'=>$key]);
            $itemPush->andWhere(['>=','creat_time',$after]);
            $time = $itemPush->min('send_time');
            $itemPush = Dictionary::find();
            $itemPush->where(['user_id'=>$key]);
            $itemPush->andWhere(['send_time'=>$time]);
            $itemPush= $itemPush->one();
            if(empty($itemPush)){
                break;
            }
            echo '$userName: '.$userName.PHP_EOL;
            $itemPush->send_time = date('Y-m-d H:i:s');
            $itemPush->save(false);
            $domain = Yii::$app->params['domain_api'];

            if(!empty($itemPush)){
                echo $itemPush->word.PHP_EOL;
                echo '$key'.$key.PHP_EOL;
                $data = [
                    'notification'=>[
                        "title"=> $itemPush->word,
                        "body"=> $itemPush->mean.' -- '.$itemPush->sentence,
                        "icon"=> $domain.$itemPush->image,
                        "click_action"=> !empty($itemPush->link)?$itemPush->link:''
                    ],
                    'to'=>$value
                ];
                $url = 'https://fcm.googleapis.com/fcm/send';
                print_r($data);
                $client = new Client();
                $response = $client->createRequest()
                    ->setFormat(Client::FORMAT_JSON)
                    ->setMethod('POST')
                    ->setUrl($url)
                    ->setHeaders($header)
                    ->setData($data)
                    ->send();
                print_r($response->getData());
                echo '------------------------'.PHP_EOL;
            }
        }

    }
}