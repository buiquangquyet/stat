<?php
/**
 * Created by PhpStorm.
 * User: nghipt
 * Date: 12/7/2015
 * Time: 5:00 PM
 */

namespace common\components;

class Ematic
{
  public static $url = "https://us14.api.mailchimp.com/3.0/lists";
  // public static $listId = "5570d4fc3d";
  public static $subscriberValue = "8f85f29047";
  public static $customerValue = "5f9df8624f";

  public static function newletterSignup($email = null, $listId, $apiKey)
  {
    if ($email == null)
      return false;

    $emailMd5 = md5($email);
    $status = "subscribed";
    $interest = array(
      "3486f596f5" => true
    );

    $arr = array(
      "email_address" => $email,
      "status_if_new" => $status,
      "interests"	 	=> 	$interest
    );

    $urlCall = self::$url . '/' . $listId . '/members/' . $emailMd5;


    return self::call($urlCall, $arr, $apiKey);
  }

  public static function accountRegistration($email = null, $name = null, $listId, $apiKey)
  {
    if ($email == null || $name == null)
      return false;

    $emailMd5 = md5($email);
    $status = "subscribed";
    $interest = array(
      "3486f596f5" => true
    );

    $arr = array(

      "email_address" => $email,
      "status_if_new" => $status,
      "merge_fields" => array(
        "NAME" => $name
      ),
      // "interests"	 	=> 	$interest,
    );

    $urlCall = self::$url . '/' . $listId . '/members/' . $emailMd5;

    return self::call($urlCall, $arr, $apiKey);
  }

  public static function createOrder($email = null, $name = null, $city = null, $listId, $apiKey)
  {
    if ($email == null || $name == null || $city == null)
      return false;
    $emailMd5 = md5($email);
    $status = "subscribed";
    $interest = array(
      "3486f596f5" => true
    );

    $arr = array(

      "email_address" => $email,
      "status_if_new" => $status,
      "merge_fields" => array(
        "NAME" => $name,
        "CITY" => $city
      ),
      // "interests"	 	=> 	$interest,
    );

    $urlCall = self::$url . '/' . $listId . '/members/' . $emailMd5;


    return self::call($urlCall, $arr, $apiKey);
  }


  public static function call($url, $data, $apiKey)
  {
    $headers = array(
      // "POST ".$page." HTTP/1.0",
      "Content-Type: application/vnd.api+json",
      "Accept: application/vnd.api+json",
      // "Cache-Control: no-cache",
      // "Pragma: no-cache",
      // "SOAPAction: \"run\"",
      // "Content-length: ".strlen($xml_data),
      "Authorization: apikey " . $apiKey
    );
    // var_dump(json_encode($data));die;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);


    curl_close($ch);
    // var_dump($output);die;
    // return Json::decode($res, false);
    return $output;


    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    // curl_setopt($ch, CURLOPT_ENCODING, '');
    // curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_HEADER, 0);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0');
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    // $res = curl_exec($ch);
    // curl_close($ch);
    // var_dump($res);die;
    // return Json::decode($res, false);
  }
}