<?php

/**
 * Created by PhpStorm.
 * User: nghipt
 * Date: 11/3/2015
 * Time: 6:12 PM
 */

namespace common\components;

use common\models\model\TaxFeeSellers;
use common\models\service\SiteService;


class CaculatePrice
{
  static $sellerTaxs = [];

  public static function getPriceLocal($sellPrice = 0, $usTax = 0, $usShipping = 0, $interShipping = 0, $customFee = 0, $deliveryFee = 0, $serviceFee = 0, $quantity = 1, $sellerId = '', $exchangeRate = 21500)
  {
    if (!$usTax && $sellerId) {
      //Lấy thuế bang
      if (isset(static::$sellerTaxs[$sellerId])) {
        $seller = static::$sellerTaxs[$sellerId];
      } else {

        $sellerCache = CacheClient::get('t_' . $sellerId, false);
        if ($sellerCache === false) {
          $seller = TaxFeeSellers::find()->where(['sellerId' => $sellerId])->one();
          static::$sellerTaxs[$sellerId] = $seller;
          CacheClient::set('t_' . $sellerId, $seller);
        } else {
          $seller = $sellerCache;
        }
      }

      if (isset($seller->usTax) && $seller->usTax > 0) {
        $usTax = $seller->usTax;
      }
    }


    $itemUSPrice = $sellPrice * (1 + $usTax / 100) + $usShipping;
    $weshopFee = $sellPrice * ($serviceFee / 100);
    $checkStore = \Yii::$app->params['checkStore'];
    $storeData = SiteService::getStore($checkStore);
    $importTax = 0; //Import tax
    if (isset($storeData->Hosts) && $storeData->Hosts == \Yii::$app->params['stotes']['weshopmy']) {
      $importTax = $sellPrice * 0.06; //Import tax
    } else if (isset($storeData->Hosts) && $storeData->Hosts == \Yii::$app->params['stotes']['weshopth']) {
      $importTax = $sellPrice * 0.10; //Import tax
    }

    $itemVNPrice = $itemUSPrice + $weshopFee + $interShipping + $customFee + $deliveryFee + $importTax;
    $itemPrice = $itemVNPrice * $exchangeRate;
    $totalPrice = $itemPrice * $quantity;


    return array(
      'itemUSPrice' => $itemUSPrice,
      'itemVNPrice' => $itemVNPrice,
      'itemPrice' => $itemPrice,
      'totalPrice' => $totalPrice,
    );
  }

  public static function buildPriceAmazon($price, $exRate)
  {
    if ($price > 0) {
      $price *= $exRate;
      $price = round($price);
    }
    return $price;
  }

}
