<?php
/**
 * Created by PhpStorm.
 * User: ducqu
 * Date: 1/5/2016
 * Time: 3:30 PM
 */

namespace common\components;


class GoogleTranslate
{

  // this is the API endpoint, as specified by Google

  const ENDPOINT = 'https://www.googleapis.com/language/translate/v2';
  // holder for you API key, specified when an instance is created
  protected static $_apiKey = 'AIzaSyDf5Oadsge9OmuQqvYhsm25z1Al-1dMdJ4';

  // translate the text/html in $data. Translates to the language
  // in $target. Can optionally specify the source language
  public static function translate($sourceText, $target = 'en', $source = '')
  {
    // this is the form data to be included with the request
    $values = array(
      'key' => static::$_apiKey,
      'target' => $target,
      'q' => $sourceText,
    );

    // only include the source data if it's been specified
    if (strlen($source) > 0) {
      $values['source'] = $source;
    }

    // turn the form data array into raw format so it can be used with cURL
    $formData = http_build_query($values);

    // create a connection to the API endpoint
    $ch = curl_init(static::ENDPOINT);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // tell cURL to return the response rather than outputting it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // write the form data to the request in the post body
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

    // include the header to make Google treat this post request as a get request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));

    // execute the HTTP request
    $json = curl_exec($ch);
    curl_close($ch);

    // decode the response data
    $data = json_decode($json, true);

    // ensure the returned data is valid
    if (!is_array($data) || !array_key_exists('data', $data)) {
      return $sourceText;
    }

    // ensure the returned data is valid
    if (!array_key_exists('translations', $data['data'])) {
      return $data;
    }

    if (!is_array($data['data']['translations'])) {
      return $data;
    }

    // loop over the translations and return the first one.
    // if you wanted to handle multiple translations in a single call
    // you would need to modify how this returns data
    foreach ($data['data']['translations'] as $translation) {
      return $translation['translatedText'];
    }

    // assume failure since success would've returned just above
    return $data;
  }

  static function translateVN($text,$target = 'en')
  {
    //$connection = RedisLanguage::getConnection();
    $text = preg_replace('/\s\s+/', ' ', trim($text));
    $key = strtolower(str_replace(' ', '-', $text));
    //$trans = $connection->executeCommand('HGET', ['en-vi-trans', $key]);
    // if (!empty($trans)) {
    //   return $trans;
    // }
      $get = \Yii::$app->request->get();
      $key = 'translate_'.$text.'-'.$target;
      $result = isset($get['nocache']) && $get['nocache'] == 'lang' ? null : Cache::get($key);
      if(!$result){
          $result = GoogleTranslate::translate($text, $target);
          Cache::set($key,$result,60*60*24*30);
      }
    //$connection->executeCommand('HSET', ['en-vi-trans', $key, $result]);

    return $result;
  }

}
