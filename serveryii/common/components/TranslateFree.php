<?php
/**
 * Created by PhpStorm.
 * User: Nhung Vien
 * Date: 12/2/2017
 * Time: 9:22 AM
 */


namespace common\components;

use Google\Cloud\Translate\TranslateClient;
use Yii;

class TranslateFree
{

    // this is the API endpoint, as specified by Google

    //const ENDPOINT = 'https://statickidz.com/scripts/traductor/?';
    const PROJECTID = 'weshop-1294';

    # Instantiates a client


    // translate the text/html in $data. Translates to the language
    // in $target. Can optionally specify the source language
    public static function translate($sourceText, $target = 'en', $source = '')
    {
//        $qr = self::ENDPOINT."source=".$source."&target=".$target."&q=".urlencode($sourceText);
////        $handle = curl_init($qr);
////        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
////        $response = curl_exec($handle);
////        curl_close($handle);
//        $data = json_decode(file_get_contents($qr,true),true);
//        if(!isset($data['translation']))
//        {
//            return $sourceText;
//        }
//        return $data['translation'];
        return $sourceText;
    }

    public static function translateGoogle($text)
    {
        $rs = Yii::$app->cache->get($text);
        if (empty($rs) && isset($rs)) {

            $translate = new TranslateClient([
                'key' => 'AIzaSyDf5Oadsge9OmuQqvYhsm25z1Al-1dMdJ4',
                'projectId' => self::PROJECTID,
                'restOptions' => [
                    'headers' => [
                        'referer' => 'http://weshop.beta.vn'
                    ]
                ]
            ]);
            # The target language
            $target = 'en';

            # Translates some text into Russian
            $translation = $translate->translate($text, [
                'target' => $target
            ]);

            // replace "&#39;" to "'"
            $translation['text'] = str_replace("&#39;", "'", $translation['text']);
            Yii::$app->cache->set($text, $translation['text'], 10);
            return $translation['text'];
        }
        return $rs;
    }

    static function translateVN($text)
    {
        //$connection = RedisLanguage::getConnection();
        $text = preg_replace('/\s\s+/', ' ', trim($text));
        $key = strtolower(str_replace(' ', '-', $text));
        //$trans = $connection->executeCommand('HGET', ['en-vi-trans', $key]);
        // if (!empty($trans)) {
        //   return $trans;
        // }
        $result = GoogleTranslate::translate($text, 'en');
        //$connection->executeCommand('HSET', ['en-vi-trans', $key, $result]);

        return $result;
    }

}