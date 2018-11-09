<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 10:53
 */

namespace frontend\views\widgets;
use common\components\Cache;
use ReflectionClass;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class BaseWidget extends Widget
{
    public $productRequest;

    public function init()
    {
        parent::init();
    }

    public function checkBand($sku)
    {
        return ProductSpecialPrice::getProductBand($sku, true);
    }

    public function getViewPath()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName());
    }

//    protected function getWebsite($currentDomain = null)
//    {
//        if ($currentDomain == null) {
//            $currentDomain = Url::base(true);
//            $currentDomain = str_replace('http://', '', $currentDomain);
//            $currentDomain = str_replace('https://', '', $currentDomain);
//        }
//
//        $storeData = Yii::$app->params['domains'][$currentDomain];
//        $storeData = array_merge($storeData, ['domain' => $currentDomain]);
//
//        $this->website = new Website($storeData);
//        return $this->website;
//    }

}