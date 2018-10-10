<?php

namespace common\components;

use common\models\model\search\ItemSearch;
use Exception;
use stdClass;
use Yii;

class StoreClient extends CacheClient
{

  private static function init()
  {
    $config = Yii::$app->params['store'];
    parent::$cache = $config['cache'];
  }

  /**
   * push item
   * @param StoreItem $item
   * @return type
   */
  public static function pushItem($item)
  {
    try {
      return self::call($item, 'item/pushitem.api', 0);
    } catch (Exception $exc) {
      return null;
    }
  }

  /**
   * get by ids
   * @param type $ids
   * @return stdClass
   */
  public static function mGet($ids = [])
  {
    try {
      return self::call($ids, 'item/gets.api');
    } catch (Exception $exc) {
      return new stdClass();
    }
  }

  /**
   * get thông tin sản phẩm
   * @param type $id
   * @return stdClass
   */
  public static function get($id)
  {
    try {
      return self::call(["id" => $id], 'item/get.api');
    } catch (Exception $exc) {
      return new stdClass();
    }
  }

  /**
   * Tìm kiếm sp theo điều kiện
   * @param \common\component\ItemSearch $search
   * @return stdClass
   */
  public static function search(ItemSearch $search)
  {
    try {
      return self::call($search, 'item/search.api');
    } catch (Exception $exc) {
      return new stdClass();
    }
  }

  /**
   * Exit item by sku
   * @param type $sourceSku
   * @return type
   */
  public static function exits($sourceSku)
  {
    try {
      $exits = self::call(["sourceSku" => $sourceSku], 'item/exits.api');
      return isset($exits->data) && $exits->data == 1 ? true : false;
    } catch (Exception $exc) {
      return false;
    }
  }

  /**
   * call service
   * @param type $params
   */
  private static function call($params, $function, $cache = 1)
  {
    self::init();
    if ($cache != 1) {
      parent::$cache = false;
    }
    if (!parent::startCache($params)) {
      try {
        $config = Yii::$app->params['store'];
        $request = new stdClass();
        $request->email = $config['email'];
        $request->code = $config['code'];
        $request->params = base64_encode(json_encode($params));
        $post = json_encode($request);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['service'] . $function);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        parent::endCache(json_decode($result));
      } catch (Exception $exc) {
        parent::endCache([]);
      }
    }
    return parent::$data;
  }

  public static function callTest($params)
  {
    try {
      $config = Yii::$app->params['store'];
      $request = new stdClass();
      $request->email = $config['email'];
      $request->code = $config['code'];
      $request->params = base64_encode(json_encode($params));

      $post = json_encode($request);
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, 'http://solr.weshop.com.vn/item/pushitem.api');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

      $output = curl_exec($ch);
      curl_close($ch);

      print_r($output);
    } catch (\Exception $ex) {
      print_r($ex);
      exit();
    }
  }

}
