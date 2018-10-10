<?php
/**
 * Created by PhpStorm.
 * @author: ducquan
 */

namespace common\components;


use common\lib\Log4P;
use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;

class RestClient
{
    static $errorId = null;

    public static function call($url, $data = null, $timeout = 40, $postJson = true, &$info = false)
    {

        $ch = curl_init();
        if ($data != null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($postJson) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Accept: application/json',
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen(json_encode($data)))
                );
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            try {
                static::$errorId = $unix = end(explode('&time=', $data));
            } catch
            (\Exception $e) {
            }

            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $res = curl_exec($ch);

//        if (!empty(curl_errno($ch))) {
//            throw new \Exception('Call API: URL:' . $url . ' Data: ' . json_encode($data) . ' Response: ' . $res, 2);
//        }
            $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http == 200) {
                $json = @json_decode($res, TRUE);
                return $json;
            } else {
                return $res;
            }

        }
    }

    public
    static function callWithBasicAuth($url, $login = null, $password = null, $data = null, $timeout = 14)
    {
        $ch = curl_init();
        if ($data != null) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        if (!empty($login) && !empty($password)) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        }

        $res = curl_exec($ch);
        $requestInfo = curl_getinfo($ch);
        curl_close($ch);

        if (!TextUtility::is_json($res) || $res == false) {
            throw new \Exception('API IllForm URL:' . $url . ' Data: ' . json_encode($data) . ' Response: ' . $res, 1);
        }

        $data = Json::decode($res, false);
        $data->requestInfo = $requestInfo;
        return $data;
    }

    public
    static function multiCall($urls = [])
    {
        $ch = [];
        $results = [];
        $mh = curl_multi_init();
        foreach ($urls as $key => $node) {

            $ch[$key] = curl_init();
            $url = null;
            $data = null;

            if (is_array($node)) {
                $url = $node[0];
                $data = $node[1];
            } else {
                $url = $node;
            }

            curl_setopt($ch[$key], CURLOPT_URL, $url);

            if ($data != null) {
                curl_setopt($ch[$key], CURLOPT_POST, 1);
                curl_setopt($ch[$key], CURLOPT_POSTFIELDS, $data);
            }

            curl_setopt($ch[$key], CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch[$key], CURLOPT_ENCODING, '');
            curl_setopt($ch[$key], CURLOPT_URL, $url);
            curl_setopt($ch[$key], CURLOPT_HEADER, 0);
            curl_setopt($ch[$key], CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch[$key], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
            curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch[$key], CURLOPT_TIMEOUT, 10);
            curl_setopt($ch[$key], CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_multi_add_handle($mh, $ch[$key]);
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            usleep(50000);
        } while ($running > 0);

        foreach ($ch as $key => $node) {
            $results[$key] = curl_multi_getcontent($ch[$key]);
            curl_multi_remove_handle($mh, $ch[$key]);
        }
        foreach ($results as $key => $value) {
            $results[$key] = Json::decode($value, false);
        }
        curl_multi_close($mh);
        return $results;
    }

    public static function callAPI($action, $data = null, $timeout = 40, $postJson = true, $info = false)
    {
        $url = Yii::$app->params['weshopApi'] . '/' . $action;
        $data = self::encrypt($data);
        $rs = self::call($url, $data, $timeout, $postJson, $info);
        $rs['data'] = self::decrypt($rs['data']);
        return $rs;
    }

    static function encrypt($string)
    {
        $data = serialize($string);

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'dgdgi@#fsdgfhuf345434';
        $secret_iv = 'weshop@0000';
        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
        return base64_encode($output);
    }

    static function decrypt($string)
    {
        $string = base64_decode($string);
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'dgdgi@#fsdgfhuf345434';
        $secret_iv = 'weshop@0000';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        return unserialize(openssl_decrypt($string, $encrypt_method, $key, 0, $iv));
    }
}

///**
// * Returns an encrypted & utf8-encoded
// */
//static function encrypt($data)
//{
//
//    $encryption_key = 's8g9es94tng@1#2';
//    $data = serialize($data);
//    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, $data, MCRYPT_MODE_ECB, $iv);
//    return base64_encode($encrypted_string);
//}
//
///**
// * Returns decrypted original string
// */
//static function decrypt($encrypted_string)
//{
//    $encrypted_string = base64_decode($encrypted_string);
//    $encryption_key = 's8g9es94tng@1#2';
//    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
//    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
//    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
//    return unserialize($decrypted_string);
//}
//}