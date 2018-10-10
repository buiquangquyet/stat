<?php
/**
 * Created by PhpStorm.
 * User: mrdoall
 * Date: 11/19/16
 * Time: 9:17 AM
 */

namespace common\components;


use common\models\model\AmazonApiSearchLog;
use common\models\model\Category;
use common\models\service\SiteService;
use common\v2\solr\amazon\ProductSearchForm;
use common\v2\solr\amazon\SolrProduct;
use common\v2\solr\amazon\SolrProductResult;
use yii\helpers\Json;
use yii\helpers\Url;

class AmazonApiV1Client
{
  static $baseClientUrl = 'http://amazonapi.weshop.asia';

  static function sortPrices($prices)
  {
    $prices = array_unique($prices);
    asort($prices);
    return $prices;
  }

  static function search(ProductSearchForm $productSearchForm, $noCache = false, $searchExactly = false, $rawPrice = false)
  {
    $sortList = [
      \common\v2\solr\amazon\ProductSearchForm::SORT_MATCH => 'relevanceblender',
      \common\v2\solr\amazon\ProductSearchForm::SORT_PRICE_ASC => 'price-asc-rank',
      \common\v2\solr\amazon\ProductSearchForm::SORT_PRICE_DESC => 'price-desc-rank',
      \common\v2\solr\amazon\ProductSearchForm::SORT_REVIEW => 'review-rank',
      \common\v2\solr\amazon\ProductSearchForm::SORT_DATE => 'date-desc-rank'
    ];

    $sortValue = 'relevanceblender';

    if (isset($sortList[$productSearchForm->sort])) {
      $sortValue = $sortList[$productSearchForm->sort];
    }

    $key = Json::encode($productSearchForm) . 'api-new';
    $result = $noCache == true ? null : \Yii::$app->cache->get($key);
    if (empty($result)) {


      $result = new SolrProductResult();

      if (!empty($productSearchForm->category)) {
        $data = RestClient::callWithBasicAuth(static::$baseClientUrl . "/amazon-api/categories/id=$productSearchForm->category/sort=$sortValue/page=$productSearchForm->page", 'amazon', 'abcd@1234!');
      } else {
        $productSearchForm->keyword = urlencode($productSearchForm->keyword);
        $data = RestClient::callWithBasicAuth(static::$baseClientUrl . "/amazon-api/search/$productSearchForm->keyword/sort=$sortValue/page=$productSearchForm->page", 'amazon', 'abcd@1234!');
      }

      foreach ($data->productList as $item) {
        $solrProductTmp = new SolrProduct();
        $solrProductTmp->ASIN = $item->ProductID;
        $solrProductTmp->Title = $item->ProductName;
        $solrProductTmp->SellPrice = floatval($item->ProductPrice);
        $solrProductTmp->OriginPrice = floatval($item->ProductRetailPrice);
        $solrProductTmp->Images = $item->Images;
        $solrProductTmp->IsPrimary = true;

//                SolrTransport::updateProduct($solrProductTmp);

        if (isset($item->ProductPrices) && !empty($item->ProductPrices)) {
          foreach ($item->ProductPrices as $productPrice) {
            if (is_numeric($productPrice) && $productPrice > 0) {
              $solrProductTmp->ProductPrices[] = $productPrice;
            }
          }
          if (!empty($solrProductTmp->ProductPrices)) {
            $solrProductTmp->ProductPrices = static::sortPrices($solrProductTmp->ProductPrices);
          } else {
            $solrProductTmp->ProductPrices[0];
          }
        }

        $result->products[] = SolrProduct::rebuildProduct($solrProductTmp, true, $productSearchForm->storeId, true, null, $rawPrice);
      }

      $storeData = SiteService::getStore(\Yii::$app->params['checkStore']);

      if ($storeData->runMode == 2) {
        $log = new AmazonApiSearchLog();
        $log->category_id = $productSearchForm->category;
        $log->search_keyword = $productSearchForm->keyword;
        $log->page = $productSearchForm->page;
        $log->item_number = count($result->products);
        $log->response_time = $data->requestInfo['total_time'];
        $log->http_status = $data->requestInfo['http_code'];
        $log->store_id = $storeData->id;
        $log->url = Url::current([], true);
        $log->save(false);
      }

      $result->attributes = [];
      $result->nodes = [];
      $result->conditions = [];
      $result->publishers = [];
      $result->brands = [];
      if (property_exists($data, 'CategoryList')) {
        foreach ($data->CategoryList as $cat) {
          if (!empty($cat->NodeID)) {
            $std = new \stdClass();
            $std->NodeId = $cat->NodeID;
            $std->NodeName = $cat->NodeName;
            $result->nodes[] = $std;
          }
        }
      }
      if (property_exists($data, 'SellerList')) {
        foreach ($data->SellerList as $cat) {
          if (!empty($cat->NodeID)) {
            $std = new \stdClass();
            $std->NodeId = $cat->NodeID;
            $std->NodeName = $cat->NodeName;
            $result->publishers[] = $std;
          }
        }
      }
      $result->perPage = count($data->productList);
      $result->count = $result->perPage * $data->NumPages;
      $result->start = $productSearchForm->page * $result->perPage - $result->perPage;
      \Yii::$app->cache->set($key, $result);
    }
    return $result;
  }

  static function getRawProduct()
  {

  }

  static function get($asinId, $noCache = false, $storeId = null, $rawPrice = false)
  {
    $key = 'amazon-api-product-' . $asinId;
    $result = $noCache == true ? null : \Yii::$app->cache->get($key);
    if (empty($result)) {

      $respData = RestClient::callWithBasicAuth(static::$baseClientUrl . "/amazon-api/product/$asinId", 'amazon', 'abcd@1234!', null, 15);
      $data = $respData->returnProductDetail;
      $result = new SolrProduct();
      $result->ASIN = $data->ProductID;

      $data->ProductPrice = TextUtility::onlyNumber($data->ProductPrice);
      $data->ProductRetailPrice = TextUtility::onlyNumber($data->ProductRetailPrice);

      if (isset($data->ShippingWeight->weight)) {
        $weight = floatval($data->ShippingWeight->weight);
        if ($weight == 0) {
          $result->ShippingWeight = 1;
        } else {
          $allowUnits = ['pounds', 'ounces', 'kg', 'gram'];
          $unit = isset($data->ShippingWeight->unit) ? $data->ShippingWeight->unit : 'pounds';
          $unit = in_array($unit, $allowUnits) ? $unit : 'pounds';
          switch ($unit) {
            case 'pounds':
              $result->ShippingWeight = $weight * 0.45359237;
              break;
            case 'ounces':
              $result->ShippingWeight = $weight * 0.0283495231;
              break;
            case 'kg':
              $result->ShippingWeight = $weight;
              break;
            case 'gram':
              $result->ShippingWeight = $weight * 0.001;
              break;
          }
        }
      }

      $result->Title = $data->ProductName;
      $result->Brand = $data->Seller;
      $result->Publisher = $data->Seller;
      $result->SellPrice = $data->ProductPrice;
      $result->OriginPrice = $data->ProductRetailPrice;
      $result->NodeIds = $data->CategoryID;
      $images = [];
      foreach ($data->Images as $image) {
        $image_tmp = explode(',', $image);
        if (count($image_tmp) > 1) {
          unset($image_tmp[0]);
          $image_tmp = reset($image_tmp);
          $image_tmp = explode('._S', $image_tmp);
          $image_tmp = $image_tmp[0] . '.jpg';
          $images[] = $image_tmp;
        }
      }
      $result->Attributes = [];
      foreach ($data->TechnicalDetails as $techSpec) {
        $result->Attributes[] = str_replace(' = ', ':|', $techSpec);
      }

//            if (!empty($data->FeatureDetails)) {
//                $result->appendDescription("\n" . implode("\n", $data->FeatureDetails));
//            }
      $result->FeatureDetails = explode('| ', $data->Description);
      $result->FeatureDetails = array_merge($result->FeatureDetails, $data->FeatureDetails);
//            die(var_dump($data));
      $result->Images = $images;
//            SolrTransport::updateProduct($result);

      $result->otherOption = [];
      foreach ($data->Sizes as $opt) {
        if (!empty($opt))
          $result->otherOption['Size'][$opt] = $opt;
      }
      foreach ($data->Styles as $opt) {
        if (!empty($opt))
          $result->otherOption['Styles'][$opt] = $opt;
      }
      foreach ($data->Colors as $opt) {
        if (!empty($opt))
          $result->otherOption['Colors'][$opt] = $opt;
      }

      $result = SolrProduct::rebuildProduct($result, true, SiteService::getStore()->id, false, null, $rawPrice);

      $storeData = SiteService::getStore(\Yii::$app->params['checkStore']);

      try {
        foreach ($respData->CategoryList as $cat) {
          $newCat = new Category();
          $newCat->alias = $cat->NodeID;
          $newCat->name = $cat->NodeName;
          $newCat->originName = $cat->NodeName;

          $result->categories[] = $newCat;
        }
      } catch (\Exception $e) {

      }

      if ($storeData->runMode == 2 && $rawPrice == false) {
        $log = new AmazonApiSearchLog();
        $log->product_id = $asinId;
        $log->price = $result->SellPrice;
        $log->color_number = count($result->otherOption['Colors']);
        $log->size_number = count($result->otherOption['Size']);
        $log->style_number = count($result->otherOption['Styles']);
        $log->description_count = strlen($result->getDescription());
        $log->image_count = count($result->Images);
        $log->response_time = $respData->requestInfo['total_time'];
        $log->http_status = $respData->requestInfo['http_code'];
        $log->store_id = $storeData->id;
        $log->type = 'PRODUCT';
        $log->url = Url::current([], true);
        $log->save(false);
      }

      if (empty($result->ASIN)) {
        throw new \Exception('Sản phẩm không tồn tại');
      }
//            \Yii::$app->cache->set($key, $result);
    }
    return $result;
  }
}