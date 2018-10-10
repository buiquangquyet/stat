<?php
/**
 * Created by PhpStorm.
 * User: haibt
 * Date: 8/14/17
 * Time: 4:09 PM
 */

namespace common\components;


use common\lib\EbaySearchForm;
use Yii;



class EbayApiV2Client
{
    const CLIENT_URL = 'http://ebay-api.weshop.asia/';
    const CLIENT_URL_SEARCH = 'http://ebay-api-v3.weshop.asia/v3/search/';

    public static function getItems($ids = [])
    {
        $requestIds = [];
        foreach ($ids as $id) {
            $requestIds[] = '"' . strval($id) . '"';
        }
        //#Todo @Phuchc EBAY 001  : need cache Resphone API Product

        return static::sendApiRequest('product/detail?ids=[' . implode(',', $requestIds) . ']');
    }

    //api detail v3
    public static function getItem($id)
    {
        return static::sendApiRequest('product/?id='.$id);
    }

    /**
     * @return response request
     */
    public static function findItems(EbaySearchForm $searchForm)
    {
        $dataPost['searchType']= 'keyword';
        if (!empty($searchForm->keywords)) {
            $dataPost['keywords']= str_replace('+', ' ', $searchForm->keywords);
        }
        if (!empty($searchForm->page) && $searchForm->page > 1) {
            $dataPost['page'] = $searchForm->page;
        }
        if (!empty($searchForm->categories)) {
            $dataToBuild['categories'] = [];
            if (!is_array($searchForm->categories)) {
                $searchForm->categories = [$searchForm->categories];
            }
            $dataPost['categoryId'] = $searchForm->categories;
        }

        if (!empty($searchForm->order)) {
            $dataPost['sortOrder'] = $searchForm->order;
        }

        if (!empty($searchForm->aspectFilters)) {
            $dataPost['aspectFilter'] = [];
            foreach ($searchForm->aspectFilters as $specific) {
                $dataPost['aspectFilter'][] = [
                    'name' => $specific['name'],
                    'value' =>$specific['value']
                ];
            }
        }

        $itemFilters = [];
        if (!empty($searchForm->sellers)) {
            $seller = [
                'name' => 'Seller',
                'value' => !is_array($searchForm->sellers) ? [$searchForm->sellers] : $searchForm->sellers,
            ];
            $itemFilters[] = $seller;
        }

        if (!empty($searchForm->type)) {
            $type = [
                'name' => 'ListingType',
                'value' => $searchForm->type == 1 ? ['Auction', 'AuctionWithBIN'] : ['FixedPrice', 'StoreInventory']
            ];
            $itemFilters[] = $type;
        }

        if (!empty($searchForm->max_price)) {
            $maxPrice = [
                'name' => 'MaxPrice',
                'value' => [strval($searchForm->max_price)]
            ];
            $itemFilters[] = $maxPrice;
        }
        if (!empty($searchForm->min_price)) {
            $maxPrice = [
                'name' => 'MinPrice',
                'value' => [strval($searchForm->min_price)]
            ];
            $itemFilters[] = $maxPrice;
        }

        if (!empty($itemFilters)) {
            $dataPost['itemFilter'] = array_merge($searchForm->itemFilters,$itemFilters);
        }else{
            $dataPost['itemFilter'] = $searchForm->itemFilters;
        }
        $bot = 1;
        if (!(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|searchengine|externalhit/i', $_SERVER['HTTP_USER_AGENT']))) {
            $bot = null;
        }
        return static::sendApiPostAjax($dataPost);
        //return static::sendApiRequest('product/search?' . http_build_query($dataToBuild));
    }


    private static function sendApiRequest($path, $debug = true)
    {
        $url = Yii::$app->params['ebay_api_v3'] == true ? Yii::$app->params['ebay_api_v3_url'] : static::CLIENT_URL;

        $bot = 1;
        if (!(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|searchengine|externalhit/i', $_SERVER['HTTP_USER_AGENT']))) {
            $bot = null;
        }

        $data = RestClient::call(
            $url . $path . '&bot='.$bot, null,
            7, false, $debug
        );
        return $data;
    }

    private static function sendApiPostAjax($data,$debug = false){
        $bot = 1;
        if (!(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|searchengine|externalhit/i', $_SERVER['HTTP_USER_AGENT']))) {
            $bot = null;
        }

        $data = RestClient::call(
            Yii::$app->params['ebay-api'],
            $data,
            7,
            true,
            $debug
        );

        return $data;
    }
}