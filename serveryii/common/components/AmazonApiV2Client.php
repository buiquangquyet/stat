<?php

namespace common\components;

use  common\models\amazon\AmazonSearchForm;
use common\models\amazon\AmazonSearchResult;
use common\models\model\AmazonApiSearchLog;
use common\models\service\SiteService;
use yii\helpers\Url;
use common\lib\Log4P;
use Yii;
use yii\httpclient\Client;

class AmazonApiV2Client
{
    const CLIENT_URL = 'http://amazonapiv2.weshop.asia/';// 'http://amazonapi.weshop.asia/';  //old: 'http://45.32.87.147/';
    const CLIENT_URL_SEARCH = 'http://amazonapiv2.weshop.asia/';//'http://amazonapi.weshop.asia/';
    //new: 'amazonapi.weshop.asia : 192.241.223.153';

    /**
     * @return string
     */
    private static function getSearchUrl()
    {
        return @self::CLIENT_URL_SEARCH . 'amazon/search';
    }

    public static function getOffers($asin_id,$store)
    {
        $client = new Client();
        $res = $client->post(
            self::getOfferUrl(),
            [
                'store' => $store,
                'asin_id' => $asin_id
            ]
        )->send();
        return $res->getData();
    }

    /**
     * @return string
     */
    private static function getDetailUrl()
    {
        return @self::CLIENT_URL . 'amazon/get';
    }

    private static function getOfferUrl()
    {
        return self::CLIENT_URL . 'amazon/get_offers';
    }

    static function postData(AmazonSearchForm $amazonSearchForm, $search = true, &$debug = true)
    {

        $amazonSearchForm->load_sub_url = !empty($amazonSearchForm->load_sub_url) ? base64_decode($amazonSearchForm->load_sub_url) : $amazonSearchForm->load_sub_url;
//        $data = RestClient::call($search ? static::getSearchUrl() : static::getDetailUrl(),
//            $amazonSearchForm->getVars(),
//            7, false, $debug
//        );
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_RAW_URLENCODED)
            ->setMethod('POST')
            ->setUrl($search ? static::getSearchUrl() : static::getDetailUrl())
            ->setData($amazonSearchForm->getVars())
            ->send();
        $data = $response->getData();
        //log4p Log mongodb
        $store = SiteService::getStoreId();
        if ($data == []) {
            $dataLog['status'] = 500;
        } else {
            $dataLog['status'] = 200;
        }
        $dataLog['action'] = \Yii::$app->controller->id;
        $dataLog['request'] = ($amazonSearchForm);
        $dataLog['sku'] = $amazonSearchForm->asin_id;
        $dataLog['store_id'] = $store;
        $dataLog['provider'] = 'AMAZON';
        //$dataLog['respone'] = ($data);
//        $dataLog['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        $dataLog['andress'] = $_SERVER['REMOTE_ADDR'];
        //Log4P::pushLogs($dataLog, "RequestAmazon/".$store->SystemCountryId .'/'. date("Y-m-d").'/'.date('H'));
        Logging::LogProduct($dataLog);
        //end log4p

        return $data;
    }
    static function getAsins($data)
    {

        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_RAW_URLENCODED)
            ->setMethod('POST')
            ->setUrl(self::CLIENT_URL."/amazon/get_asins")
            ->setData($data)
            ->send();
        $data = $response->getData();

        return $data['response'] ? $data['response'] : [];
    }


    /**
     * @param $asinId
     * @param null $parentAsinId
     * @param bool $cacheOnly
     * @return \common\models\amazon\AmazonProduct|null
     */
    static function getProduct($asinId, $parentAsinId = null, $cacheOnly = false)
    {
        /*
        $nocache = \Yii::$app->request->get('clear') == 'yess';
        $cacheKey = 'nap-asin-' . $asinId . (empty($parentAsinId) ? '-m' : '-s');
        $product = null;
        if (!$nocache) {
            $product = \Yii::$app->cache->get($cacheKey);
        }
        if (!empty($product)) {
            return $product;
        } else if ($cacheOnly) {
            return null;
        }
        */

        if (empty($parentAsinId)) {
            $searchForm = new AmazonSearchForm();
            $searchForm->asin_id = $asinId;
            $product = new AmazonProduct();
            $debug = true;

            $product->loadData(static::postData($searchForm, false, $debug)->response);

            if (!empty($debug)) {
                static::logRequest('GET_NEW', $debug, $searchForm, $product);
            }

            $product->initSuggestProduct();
            //\Yii::$app->cache->set($cacheKey, $product, 86400);
            return $product;
        }

        $product = static::getProduct($parentAsinId);

        $debug = true;

        $searchForm = new AmazonSearchForm();
        $searchForm->asin_id = $asinId;
        $searchForm->parent_asin_id = $parentAsinId;
        $searchForm->load_sub_url = $product->load_sub_url;
        $product->loadSubProduct(static::postData($searchForm, false, $debug)->response);

        if (!empty($debug)) {
            static::logRequest('GET_NEW', $debug, $searchForm, $product);
        }

        //\Yii::$app->cache->set($cacheKey, $product, 86400);
        return $product;
    }

    static function search(AmazonSearchForm $searchForm)
    {
        //$nocache = \Yii::$app->request->get('clear') == 'yess';
        $cacheKey = json_encode($searchForm->getVars());
        $searchResult = null;
//        if (!$nocache) {
//            $searchResult = \Yii::$app->cache->get($cacheKey);
//            if (!empty($searchResult) && $searchResult['total_product'] > 0) {
//                return $searchResult;
//            }
//        }
        $debug = true;

        $searchResult = static::postData($searchForm, true, $debug);
//        if (!empty($debug)) {
//            static::logRequest('SEARCH_NEW', $debug, $searchForm, $searchResult);
//        }
//        if ($searchResult['total_product'] > 0) {
//            \Yii::$app->cache->set($cacheKey, $searchResult, 86400);
//        }
        return $searchResult;
    }

    static function searchProduct($keyword = null, $categoryId = null, $param = null, $sort = null, $maxPrice = null, $minPrice = null, $page = 1)
    {

        $searchForm = new AmazonSearchForm();
        $searchForm->keyword = $keyword;
        $searchForm->node_ids = $categoryId;
        $searchForm->rh = $param;
        $searchForm->sort = $sort;
        $searchForm->max_price = $maxPrice;
        $searchForm->min_price = $minPrice;
        $searchForm->page = $page;

        $nocache = \Yii::$app->request->get('clear') == 'yess';
        $cacheKey = json_encode($searchForm->getVars());
        $searchResult = null;
        if (!$nocache) {
            $searchResult = \Yii::$app->cache->get($cacheKey);
            if (!empty($searchResult) && $searchResult->total_product > 0) {
                return $searchResult;
            }
        }

        $searchResult = new AmazonSearchResult();

        $debug = true;

        $searchResult->loadData(static::postData($searchForm, true, $debug)->response);

        if (!empty($debug)) {
            static::logRequest('SEARCH_NEW', $debug, $searchForm, $searchResult);
        }
        if ($searchResult->total_product > 0) {
            \Yii::$app->cache->set($cacheKey, $searchResult, 86400);
        }
        return $searchResult;
    }

    static function logRequest($type = 'SEARCH_NEW', $debug, $searchForm, $result)
    {
        $storeData = SiteService::getStore(true);

        if ($storeData->runMode != 2) {
            return false;
        }

        $log = new AmazonApiSearchLog();
        if ($type == 'SEARCH_NEW') {
            $log->item_number = count($result->products);
            $log->search_keyword = $searchForm->keyword;
            $log->page = $searchForm->page;
            $log->category_id = $searchForm->node_ids;
        } else {
            $log->product_id = $searchForm->asin_id;
            $log->price_range = json_encode($result->sell_price);
            $log->search_keyword = $result->title;
        }
        $log->response_time = $debug['total_time'];
        $log->http_status = $debug['http_code'];
        $log->store_id = $storeData->id;
        $log->type = $type;
        $log->url = Url::current([], true);
        $log->save(false);
    }

}