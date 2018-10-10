<?php

/**
 * Created by PhpStorm.
 * User: nghipt
 * Date: 11/20/2015
 * Time: 2:48 PM
 */

namespace common\components;

use common\models\model\Product;
use common\models\model\Transaction;
use common\models\model\TransactionAccount;
use common\models\enu\SiteConfig;
use common\models\enu\StoreConfig;
use common\models\service\OrderService;
use yii\base\Exception;

class TransactionAuction
{

  public static function saveAuction($money)
  {

    $transactionAccount = TransactionAccount::findOne(['CustomerId' => \Yii::$app->user->identity->id]);
    if (empty($transactionAccount)) {
      return false;
    }
    $transaction = new Transaction();
    $transaction->TransactionAccountId = $transactionAccount->id;
    $transaction->TransactionCode = $transactionAccount->AccountNumber;
    $transaction->CustomerId = \Yii::$app->user->identity->id;
    $transaction->CreatedDate = date('Y-m-d H:i:s');
    $transaction->Status = 0;
    $transaction->Note = "Đấu giá";
    $transaction->TypeTransaction = 3;
    $transaction->DebitAmountInLocalCurrency = (int)$money;
    $transaction->save();

    return true;
  }

  public static function canelAuction($money)
  {
    $transactionAccount = TransactionAccount::findOne(['CustomerId' => \Yii::$app->user->identity->id]);
    if (empty($transactionAccount)) {
      return false;
    }
    $transaction = new Transaction();
    $transaction->TransactionAccountId = $transactionAccount->id;
    $transaction->TransactionCode = $transactionAccount->AccountNumber;
    $transaction->CustomerId = \Yii::$app->user->identity->id;
    $transaction->CreatedDate = date('Y-m-d H:i:s');
    $transaction->Status = 0;
    $transaction->Note = "Đấu giá";
    $transaction->TypeTransaction = 3;
    $transaction->DebitAmountInLocalCurrency = (int)$money;
    $transaction->save();

    return true;
  }

  public static function saveProductAuction($item)
  {
    try {
      $item = OrderService::buildItem($item, null, 1, null);
      $storeId = StoreConfig::EBAY_VN;
      $siteId = SiteConfig::EBAY_VN;
      $productItem = new Product ();
      $productItem->Sku = $item->sku;
      $productItem->siteId = $siteId;
      $productItem->StoreId = $storeId;
      $productItem->Name = $item->Name;
      $productItem->StockQuantity = $item->quantity;
      if ($item->maxQuantity - $item->quantity > 0) {
        $productItem->AvailableStockQuantity = 1;
      } else {
        $productItem->AvailableStockQuantity = 0;
      }
      $productItem->OrderMinimumQuantity = 1;
      $productItem->OrderMaximumQuantity = $item->maxQuantity;
      $productItem->Price = $item->UnitPriceInclTax;
      $productItem->OldPrice = $item->PriceInclTax;
      $productItem->ProductCost = $item->UnitPriceInclTax;
      $productItem->Weight = $item->weight;
      $productItem->CreatedTime = date("Y-m-d H:i:s");
      $productItem->UpdatedTime = date("Y-m-d H:i:s");
      $productItem->ThumbUrl = $item->image;
      $productItem->ImageUrl = $item->image;
      $productItem->save(false);

      return $productItem;
    } catch (Exception $ex) {
      return false;
    }
  }
}
