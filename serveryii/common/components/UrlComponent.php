<?php

namespace common\components;

use common\models\model\CustomerFollowed;
use common\models\model\Store;
use common\models\enu\ObjectType;
use common\models\enu\SiteConfig;
use common\models\enu\StoreConfig;
use common\models\service\SiteService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseUrl;
use yii\helpers\Url;

/**
 * Class UrlComponent
 * @package common\components
 * @property $baseUrl static string
 */
class UrlComponent
{

    static $ebaySitePath = [
        0 => 'ebay',
        7 => 'ebay',
    ];

    private static $_baseUrl;
    public static function setBaseUrl($baseUrl)
    {
        self::$_baseUrl = $baseUrl;
    }
    public static function getBaseUrl(){
        if(!self::$_baseUrl){
            $website = (new \common\models\weshop\Website())->load();
            self::$_baseUrl = $website->getUrl();
        }
        return self::$_baseUrl;
    }
    static function replaceSiteUrlEbay($cUrl)
    {
        $currentUrl = substr($cUrl, 1);

        $explodeUrl = explode('/', $currentUrl);
        if (count($explodeUrl) == 1) {
            $explodeUrl = explode('.', $currentUrl);
        }

        $sitePrefix = $explodeUrl[0];

        $sitePath = UrlComponent::getEbaySitePath();
        if ($sitePath != $sitePrefix) {
            return str_replace('/' . $sitePrefix, '/' . $sitePath, $cUrl);
        }
        return false;
    }

    static function getEbaySitePath()
    {
        $checkStore = \Yii::$app->params['checkStore'];
        $storeData = SiteService::getStore($checkStore);
        if (!empty($storeData)) {
            $storeId = $storeData->id;
            return isset(static::$ebaySitePath[$storeId]) ? static::$ebaySitePath[$storeId] : static::$ebaySitePath[0];
        } else {
            return static::$ebaySitePath[0];
        }
    }

    public static function walletTopupSuccess($id)
    {
        return Url::base(true) . '/wallet/paymenttopup.html?id=' . $id;
    }

    static function objectUrl($objectType, $objectName, $objectId = null)
    {
        switch ($objectType) {
            case ObjectType::ebay_item:
                return static::item($objectName, $objectId);
            case ObjectType::ebay_seller:
                return static::seller($objectName);
            case ObjectType::order:
                return static::order_info($objectId);
            default:
                return '';
        }
    }

    public static function allCategory()
    {
        return Url::base() . '/categoryall.html';
    }

    public static function allEbayCategory()
    {
        return Url::base() . '/categories.html?tab=' . UrlComponent::getEbaySitePath();
    }

    public static function allAmazonCategory()
    {
        return Url::base() . '/categories.html?tab=amazon';
    }

    public static function landingAddressUs()
    {
        return Url::base() . '/address-us.html';
    }

    public static function notfound()
    {
        return Url::base() . '/404.html';
    }

    public static function info($id)
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/order-' . $id . '/bill.html';
    }

    /**
     *
     * @return string
     */
    public static function logout()
    {
        return "auth/logout.html";
    }

    public static function walletUrl($extend)
    {
        return Url::base() . '/wallet/topup.html';
    }

    public static function userUrl($extend)
    {
        return Url::base() . '/user/' . $extend;
    }

    public static function login()
    {
        return Url::base() . '/login.html';
    }

    public static function payment($id)
    {
        return Url::base() . '/order-' . $id . '/bill.html';
    }

    public static function payment_mail($id)
    {
        return 'order-' . $id . '/bill.html';
    }

    public static function bill($id)
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/order-' . $id . '/bill.html';
    }

    public static function over_counter($id, $data)
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/C2P2-' . $id . '/success.html?transDt=' . $data['transaction_ref'] . '&transTm=' . $data['transaction_datetime'] . '&paid_agent=' . $data['paid_agent'];
    }

    public static function bill_mail($id)
    {
        return 'order-' . $id . '/bill.html';
    }

    public static function bill_topup($token)
    {
        return 'account/detailtopup.html?token=' . $token;
    }

    public static function addfee_mail($id)
    {
        return 'addfee-' . $id . '/bill.html';
    }

    public static function register()
    {
        return Url::base() . '/register.html';
    }

    public static function auth($authClient = 'facebook')
    {
        return '/weshop/login/auth?authclient=' . $authClient;
    }

    public static function step3_bill($id)
    {
        return '/order-' . $id . '/bill.html';
    }

    public static function step3_addfee($id)
    {
        return '/addfee-' . $id . '/bill.html';
    }

    public static function step3_wallettransaction($id){
        return '/account/wallettransaction/' . $id . '/bill.html';
    }

    public static function step3_Cancelwallettransaction($id){
        return '/account/canceltransaction/' . $id . '/bill.html';
    }

    public static function step_pending($id)
    {
        return '/order-' . $id . '/pending.html';
    }

    public static function tracking_order($id)
    {
        return '/order-' . $id . '/tracking.html';
    }

    public static function auctionsuccess()
    {
        return Url::base(true) . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/auctionsuccess';
    }

    public static function tracking_orderitem($id)
    {
        return '/order-' . $id . '/trakingitem.html';
    }

    /**
     * Trang báo giá
     * @param type $link
     * @return type
     */
    public static function quotes($link = null)
    {
        return Url::base() . "/request.html";
    }

    /**
     * Trang thông tin báo giá đã đặt hàng
     * @param type $id
     * @return type
     */
    public static function quotes_detail()
    {
        return Url::base() . "/resquest/quotes.html";
    }

    /**
     * Dùng để chuyển hướng link sản phẩm
     * @param type $link
     * @return type
     */
    public static function detail($link)
    {
        $link = base64_encode($link);
        return "redirect/item.html?link=" . $link;
    }

    /**
     * detail global khi các site chưa có port riêng
     * @param type $id
     * @param type $name
     * @param type $query
     * @return type
     */
    public static function item_detail_global($id, $name, $query = null)
    {
        return "item/" . TextUtility::removeMarks($name) . "-" . $id . '.html' . (empty($query) ? '' : $query);
    }

    public static function seller($name, $siteId = SiteConfig::EBAY_VN)
    {
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/seller/' . $name . '.html';
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = Url::base() . '/amazon/seller/' . $name . '.html';
                break;
            default:
                $url = Url::base() . '/nguoi-ban/' . $name . '.html';
                break;
        }
        return $url;
    }

    public static function item($name, $id, $siteId = SiteConfig::WESHOP_EBAY, $storeId = StoreConfig::WESHOP_GLOBAL)
    {
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = self::getBaseUrl() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = self::getBaseUrl() . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
            default:
                $url = self::getBaseUrl() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
        }
        return $url;
    }

    public static function item_mail($name, $id)
    {
        return 'san-pham/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function listOrderWallet()
    {
        return 'wallet/listwallet';
    }

    public static function search($keyword, $catIds = null, $siteId = SiteConfig::EBAY_VN)
    {
        $catParams = !empty($catIds) ? '?categoryIds[]=' . strval($catIds) : '';
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = Url::base() . '/search/' . urlencode($keyword) . '.html' . $catParams;
                break;
            default:
                $url = Url::base() . '/tim-kiem/' . urlencode($keyword) . '.html' . $catParams;
                break;
        }
        return $url;
    }

    public static function category($name, $id)
    {
        return Url::base() . '/danh-muc/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function auction($name, $id)
    {
        return static::item($name, $id);
        return Url::base() . '/auction/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    /**
     * item detail
     * @param type $id
     * @param type $name
     * @param type $query
     * @return type
     */
    public static function item_detail($id, $name, $siteId = SiteConfig::WESHOP_EBAY, $storeId = StoreConfig::WESHOP_GLOBAL)
    {
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = Url::base(true) . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = Url::base(true) . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
            default:
                $url = Url::base(true) . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
                break;
        }
        return $url;
    }

    /**
     * landing detail
     * @param type $id
     * @param type $name
     * @param type $query
     * @return type
     */
    public static function landing_detail($id, $name, $query = null)
    {
        return "landing/" . TextUtility::removeMarks($name) . "-" . $id . '.html' . (empty($query) ? '' : $query);
    }

    public static function landing_deals($hostName, $id, $name)
    {
        return $hostName . "/landing-deals/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    public static function landing_product($hostName, $id, $name)
    {
        return $hostName . "/landing-product/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    // item description
    public static function item_description($id, $siteId = SiteConfig::WESHOP_EBAY)
    {

        $url ="/product-description-" . $id . '.html';
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url =  "/" . \common\components\UrlComponent::getEbaySitePath() . "/product-description-" . $id . '.html';
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = "/amazon/product-description-" . $id . '.html';
                break;
            default:
                $url =  "/product-description-" . $id . '.html';
                break;
        }
        return $url;

    }

    // item description
    public static function item_description_frame($id, $siteId = SiteConfig::WESHOP_EBAY)
    {
        if ($siteId == SiteConfig::WESHOP_EBAY)
            return "ebay/product-description-" . $id . '.html';
        else
            return "product-description-" . $id . '.html';

    }

    /**
     * Search
     * @param type $keyword
     * @return type
     */
    public static function search_global($keyword)
    {
        return "s/" . (empty($keyword) ? 'weshop' : $keyword) . ".html";
    }

    /**
     * Danh mục
     * @param type $id
     * @param type $name
     * @return type
     */
    public static function browse($id, $name, $siteId = SiteConfig::EBAY_VN)
    {
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = self::getBaseUrl() . "/" . \common\components\UrlComponent::getEbaySitePath() . "/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = self::getBaseUrl() . "/amazon/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
                break;
            case SiteConfig::CATEGORY_AMAZON_NEW:
                $url = self::getBaseUrl() . "/amazon/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
                break;
            default:
                $url = self::getBaseUrl() . "/danh-muc/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
                break;
        }
        return $url;
    }

    /**
     * Trang toàn bộ danh mục
     * @return string
     */
    public static function categories()
    {
        return Url::base() . "/danh-muc.html";
    }

    /**
     * Trang toàn bộ thông báo
     * @return string
     */
    public static function notification()
    {
        return Url::base() . "/user/notification";
    }

    /**
     * Trang check out steep 1
     * @return string
     */
    public static function order_steep_one()
    {
        return Url::base() . "/carts.html";
    }

    /**
     * Trang check out steep 2
     * @param type $orderId
     * @return type
     */
    public static function order_steep_two($orderId)
    {
        return "order-" . $orderId . "/bill.html";
    }

    /**
     * Trang check out steep 3, mặc định là trang thông báo thông tin đơn hàng
     * @param type $orderId
     * @param type $condition
     * @return string
     */
    public static function order_steep_three($orderId, $condition = null)
    {
        /* Trang thông báo  */
        $link = "order-" . $orderId . "/support.html";
        switch ($condition) {
            case "support":
                /* Hỗ trợ thanh toán */
                $link .= "?support=true";
                break;
        }
        return $link;
    }

    public static function order_feemore($orderId)
    {
        /* Trang thông báo  */
        $link = "order-" . $orderId . "/feemore.html?feemore=true";
        return $link;
    }

    /**
     * Trang đón nhận thanh toán thành công ngân lượng
     * @param type $orderId
     * @return type
     */
    public static function order_check_payment($orderId)
    {
        return "order-" . $orderId . "/check.html";
    }

    /**
     * Trang chi tiết đơn hàng
     * @param type $orderId
     * @return type
     */
    public static function order_info($orderId)
    {
        return static::info($orderId);
    }

    /**
     * Trang user
     * @return string
     */
    public static function user_info()
    {
        return "user.html";
    }

    /**
     * Trang danh sách đơn hàng
     * @return string
     */
    public static function user_order()
    {
        return "user/order.html";
    }

    /**
     * Trang danh mục tin tổng hợp
     * @return string
     */
    public static function tin_th()
    {
        return "n/tin-tong-hop.html";
    }

    /**
     * Trang danh mục tin tổng hợp
     * @return string
     */
    public static function cs_qd()
    {
        return "n/chinh-sach-quy-dinh.html";
    }

    /**
     * Bảng giá dịch vụ
     * @return type
     */
    public static function ud_gia()
    {
        return self::news_detail('chinh-sach-gia');
    }

    /**
     * Quy chế hoạt động
     * @return type
     */
    public static function qc_hd()
    {
        return self::news_detail('quy-che-hoat-dong');
    }

    /**
     * all website
     * @return type
     */
    public static function website()
    {
        return "website.html";
    }

    public static function createOrder()
    {
        return "order/create";
    }

    /**
     *
     */
    public static function home($siteId = SiteConfig::WESHOP_GLOBAL)
    {
        $url = Url::base() . "/";
        switch ($siteId) {
            case SiteConfig::WESHOP_GLOBAL:
                $url = Url::base() . "/";
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = Url::base() . "/amazon.html";
                break;
            case SiteConfig::WESHOP_EBAY:
                $url = Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . ".html";
                break;
            default:
                $url = Url::base() . "/";
                break;
        }
        return $url;
    }

    public static function all_cat($siteId = SiteConfig::EBAY_VN)
    {
        $url = Url::base() . "/";
        switch ($siteId) {
            case SiteConfig::WESHOP_EBAY:
                $url = Url::base() . "/" . \common\components\UrlComponent::getEbaySitePath() . "/categories.html";
                break;
            case SiteConfig::WESHOP_AMAZON:
                $url = Url::base() . "/amazon/categories.html";
                break;
            default:
                $url = Url::base() . "/";
                break;
        }
        return $url;
    }

    // Url News
    public static function news()
    {
        return Url::base() . "/news.html";
    }

    public static function news_category($id, $name)
    {
        return Url::base() . "/news/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    public static function news_detail($id, $name)
    {
        return Url::base() . "/news/detail/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    // Url FAQ
    public static function faq()
    {
        return Url::base() . "/faq.html";
    }

    public static function faq_category($id, $name)
    {
        return Url::base() . "/faq/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    public static function faq_detail($id, $name)
    {
        return Url::base() . "/faq/detail/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    // Url Help
    public static function helps()
    {
        return Url::base() . "/helps.html";
    }

    public static function helps_category($id, $name)
    {
        return Url::base() . "/helps/category/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    public static function helps_detail($id, $name)
    {
        return Url::base() . "/helps/detail/" . TextUtility::removeMarks($name) . "-" . $id . '.html';
    }

    public static function walletExportexcel()
    {
        return Url::base() . "/wallet/exportexcel";
    }

    /*
    * @2017 Detech Mobile Payment
    */
    public static function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }


    public static function baseUrlWallet()
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvn']) {
            $isMobile = self::isMobile();
            if ($isMobile) {
                // Mobile
                if (YII_DEBUG == true and YII_ENV == 'dev') {
                    // Mobile develop
                    $domain = 'http://weshop.beta.vn';
                } else {
                    // Mobile Pro live mobile ENV
                    $domain = 'http://weshop.com.vn';
                }
            } else {
                // Desktop  Pro live  ENV
                $domain = Yii::$app->params['stotes']['weshopvnuser'];

                // Desktop develop
                if (YII_DEBUG == true and YII_ENV == 'dev') {
                    $domain = 'http://weshop.beta.vn';
                }
            }
        }
        return $domain;
    }

    public static function userToFront()
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain;
    }

    public static function wallet()
    {
        return Url::base() . "/wallet/listwallet";
    }

    public static function getThumbEbay($originLink, $size = 220)
    {
        if (strpos($originLink, 'ebaystatic.com') !== false || strpos($originLink, 'ebayimg.com') === false) {
            return $originLink;
        }
        $parse = explode('/', $originLink);
        $id = $parse[count($parse) - 2];
        if ((strlen($id) < 5) || (!preg_match("/^[a-zA-Z0-9]+$/", $id) == 1)) {
            return $originLink;
        }
        $size = is_int($size) ? $size : 120;
        return 'https://i.ebayimg.com/images/g/' . $id . '/s-l' . $size . '.jpg';
    }

    public static function verifyCustomer($uid, $token)
    {
        return Url::base(true) . "/verify/" . $uid . "-" . $token . '.html';
    }

    public static function setNewPassword($uid, $token)
    {
        return Url::base(true) . "/reset-password/" . $uid . "-" . $token . '.html';
    }

    public static function forgotPassword()
    {
        return Url::base(true) . '/forgot-password.html';
    }

    public static function w_shipping()
    {
        return Url::base() . '/shipping.html';
    }

    public static function w_shopping_cart()
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/shoppingcarts.html';
    }

    // Weshop global
    public static function messageDetail($id, $name)
    {
        return Url::base() . '/account/message/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function messageRead($id)
    {
        return Url::base() . '/message/read-' . $id . '.html';
    }

    public static function brandsWeshop()
    {
        return Url::base() . '/brands.html';
    }
    public static function landingRequest()
    {
        return Url::base() . '/landing-request/lp-request-98.html';
    }

    public static function weshopBenefit()
    {
        return Url::base() . '/about-us.html';
    }

    public static function weshopServicePricing()
    {
        return Url::base() . '/service-pricing.html';
    }

    public static function pasteLinkStep1Weshop($url = null)
    {
        if (!empty($url)) {
            return Url::base() . '/paste-link.html?rel=' . $url;
        }
        return Url::base() . '/paste-link.html';
    }

    public static function pasteLinkStep2Weshop()
    {
        return Url::base() . '/paste-link-result.html';
    }

    public static function pasteLinkResult($url)
    {
        return Url::base() . '/paste-link-result.html?url=' . $url;
    }

    // Weshop global ebay
    public static function indexEbay()
    {
        return Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . '.html';
    }

    public static function detailEbay($id, $name)
    {
        $domain = self::getBaseUrl();
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function detailSourceEbay($id, $name)
    {
        return Url::base(true) . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function detailSourceEbayConsole($id, $name, $domain)
    {
        return $domain . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function detailSourceAmazonConsole($id, $name)
    {
        return Url::base(true) . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function sellerEbay($id)
    {
        return Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/seller/' . $id . '.html';
    }

    public static function categorysEbay()
    {
        return Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/categories.html';
    }

    public static function dailyDealsEbay()
    {
        return Url::base() . '/' . \common\components\UrlComponent::getEbaySitePath() . '/daily-deals.html';
    }

    // Weshop global amazon
    public static function indexAmazon()
    {
        return Url::base() . '/amazon.html';
    }

    public static function detailAmazon($id, $name)
    {
        $domain = self::getBaseUrl();
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        return $domain . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function detailAmazonConsole($id, $name, $domain = 'http://weshop.com.vn')
    {
        return $domain . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function detailSourceAmazon($id, $name)
    {
        return Url::base(true) . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function UrlProductWeshop($id, $name, $type = 'amazone', $domain = 'https://weshop.com.vn/')
    {

        if ($type == 'amazone') {
            $url = $domain . '/amazon/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
        } else {
            $url = $domain . '/ebay/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
        }

        return $url;
    }


    public static function current(array $params = [], $scheme = false)
    {
        $currentParams = \Yii::$app->getRequest()->getQueryParams();
        $currentParams[0] = '/' . \Yii::$app->controller->getRoute();
        $route = ArrayHelper::merge($currentParams, $params);
        return BaseUrl::toRoute($route, $scheme);
    }

    static function WeshopAccountUrl($url)
    {
        return Url::base(true) . '/account/' . $url;
    }

    public static function followObjectUrl($object)
    {
        switch ($object->objectType) {
            case CustomerFollowed::ebay_item:
            case CustomerFollowed::ebay_auction:
                return self::detailEbay($object->objectId, $object->name);
                break;
            case CustomerFollowed::ebay_seller:
                return self::sellerEbay($object->objectId);
                break;
            case CustomerFollowed::search:
                return Url::base(true) . '/' . $object->note;
                break;
            case CustomerFollowed::amazon_item_solr:
            case CustomerFollowed::amazon_item:
                return static::detailAmazon($object->objectId, $object->name);
                break;
            case CustomerFollowed::paste_link_product:
                return static::pasteLinkResult(json_decode($object->object)->url);
                break;
        }
    }

    static function invoice($invoiceId)
    {
        return Url::toRoute('account/invoice/invoice') . '?id=' . $invoiceId;
    }

    static function proforma($invoiceId)
    {
        return Url::toRoute('account/invoice/proforma') . '?id=' . $invoiceId;
    }

    static function detectStoreId()
    {
        $domain = Url::base(true);
        if ($domain == Yii::$app->params['stotes']['weshopvnuser']) {
            $domain = Yii::$app->params['stotes']['weshopvn'];
        }
        $store = Store::find()->where(['Url' => $domain])->one();
        if ($store != false) {
            return $store->id;
        }
        return 0;
    }

    public static function sourceDetailAmazon($id, $name)
    {
//        http://www.amazon.com/exec/obidos/ASIN/{mã sản bản ASIN}/{trackid của weshop}
//        Hiện trackid của weshop là wp034-20.
        return 'https://www.amazon.com/gp/product/' . strtoupper($id) . '?ie=UTF8&tag=wp034-20&camp=1789&linkCode=xm2&creativeASIN=' . strtoupper($id);
    }

    public static function checkout($buynow = false)
    {
        $domain = self::getBaseUrl();
        if ($buynow) {
            return $domain . '/checkout.html?type=buynow';
        }
        return $domain . '/checkout.html?type=shopping';
    }

    public static function detailEbayConsole($id, $name,$domain = 'http://weshop.con.vn')
    {
        return $domain . '/' . \common\components\UrlComponent::getEbaySitePath() . '/item/' . TextUtility::getUrlAlias($name) . '-' . $id . '.html';
    }

    public static function ChangePage($page,$start=null,$end=null){
        $url = '/account/wallet/listtransaction?page='.$page;
        if($start){
            $url.='&start='.$start;
        }
        if($end){
            $url.='&end='.$end;
        }

        return $url;
    }

    public static function ChangePageIndex($page,$start=null,$end=null){
        $url = '/account/wallet/index?page='.$page;
        if($start){
            $url.='&start='.$start;
        }
        if($end){
            $url.='&end='.$end;
        }

        return $url;
    }

    public function UrlExport($start=null,$end = null,$payment_type=null){
        $url = '/account/wallet/export?';
        if($start){
            $url.='start='.$start;
        }
        if($end){
            $url.='&end='.$end;
        }
        if($payment_type){
            $url.='&payment_type='.$payment_type;
        }
        return $url;
    }

    function Redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }

}
