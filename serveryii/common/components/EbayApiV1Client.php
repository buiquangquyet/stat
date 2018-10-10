<?php
/**
 * Created by PhpStorm.
 * User: ducquan
 * Date: 12/10/2015
 * Time: 15:47 PM
 */

namespace common\components;


use common\models\ebay\SearchForm;
use yii\debug\models\search\Log;
use yii\helpers\Json;
use common\lib\Log4P;

class EbayApiV1Client
{

//    const baseUrl = 'http://10.0.0.99:8080/ebayapi/';
//    const baseUrl = 'http://23.227.167.184:8080/ebayapi/';

  public static function getCategory()
  {
    return RestClient::call(self::buildUrl('GetCategories'));
  }

  public static function getItems($ids = [])
  {
    $ids = is_array($ids) ? $ids : [strval($ids)];
    return RestClient::call(self::buildUrl('GetItems'), self::buildPostField($ids));
  }

  public static function getSeller($ids = [])
  {
    $ids = is_array($ids) ? $ids : [strval($ids)];
    return RestClient::call(self::buildUrl('GetSellers'), self::buildPostField($ids));
  }

  public static function getItemsDescription($ids = [])
  {
    $ids = is_array($ids) ? $ids : [strval($ids)];
    return RestClient::call(self::buildUrl('GetItemsDescription'), self::buildPostField($ids));
  }

  public static function findItems(SearchForm $searchForm, $include = false)
  {
    //Log4P::debug("Ebay API Client::findItem: " . json_encode(self::buildUrl('FindItems')));
    //Log4P::debug("Ebay API Client::findItem: searchForm => " . json_encode(self::buildPostField($searchForm)));
    return RestClient::call(self::buildUrl('FindItems'), self::buildPostField($searchForm));
  }

  public static function getItemsShipping($ids = [])
  {
    $ids = is_array($ids) ? $ids : [strval($ids)];
    $result = [];
    $resultTmp = RestClient::call(self::buildUrl('GetItemsShipping'), self::buildPostField($ids));
    return $resultTmp;
  }

  public static function getItemFinal($id)
  {
    if ($id == null) {
      return null;
    }
    return RestClient::multiCall([
      'item' => [self::buildUrl('GetItems'), self::buildPostField([$id])],
      'shipping' => [self::buildUrl('GetItemsShipping'), self::buildPostField([$id])]
    ]);
  }

  public static function getItemsFinal($ids)
  {
    if ($ids == null) {
      return [];
    }
    $request = [];
    foreach ($ids as $id) {
      $request['item' . $id] = [self::buildUrl('GetItems'), self::buildPostField([$id])];
      $request['shipping' . $id] = [self::buildUrl('GetItemsShipping'), self::buildPostField([$id])];
      $request['description' . $id] = [self::buildUrl('GetItemsDescription'), self::buildPostField([$id])];
    }
    //var_dump($request);die; Le Quang Tuon
    return RestClient::multiCall($request);
  }

  private static function buildUrl($url = '')
  {
    return \Yii::$app->params['ebayAPI'][0] . $url;
  }

  private static function buildPostField($data)
  {
    foreach ($data as $key => $val) {
      if ($val == '' || $val == null) {
        unset($data->$key);
      }
    }
    return 'request=' . json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    return 'request=' . Json::encode($data);
  }
}