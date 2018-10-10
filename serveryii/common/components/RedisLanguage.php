<?php
/**
 * Created by PhpStorm.
 * User: nghipt
 * Date: 10/30/2015
 * Time: 8:56 AM
 */

namespace common\components;

use common\models\db_cms_slave\LocaleStringResource;
use common\models\db_cms\LocaleStringResource as MessageSource;
use common\models\enu\Language;
use common\models\service\LanguageService;
use common\models\weshop\Website;
use Exception;
use Yii;
use yii\caching\ApcCache;
use yii\helpers\Url;
use yii\redis\Connection;

class RedisLanguage
{


    static function getLanguageInDb($key, $languageId = null, $defaultValue = '')
    {
        $languageValue = LocaleStringResource::find()->where(['active' => 1, 'ResourceName' => $key, 'LanguageId' => $languageId])->one();
        if (!empty($languageValue)) {
            return $languageValue->ResourceValue;
        }
        return static::addNewKey($key, $languageId, $defaultValue);
    }

    static function addNewKey($key, $languageId, $defaultValue = '')
    {
        $localeStringResource = new MessageSource();
        $localeStringResource->LanguageId = $languageId;
        $localeStringResource->ResourceName = $key;
        $localeStringResource->ResourceValue = $defaultValue;
        $localeStringResource->Active = 1;
        $localeStringResource->Type = 1;
        try {
            $localeStringResource->save(false);
            return $defaultValue;
        } catch (\Exception $e) {
            return $defaultValue;
        }

    }

    public static function getLanguageByKey($key, $defaultValue = '', $languageId = null)
    {
        $website = new Website();
        if ($languageId == null) {
            $languageId = $website->getStoreData()->LanguageId;
        }
        $languageId = !empty($languageId) ? $languageId : 2;

        $cacheKey = 'i18n-l-' . $languageId . '-k-' . $key;
        if (is_a(Yii::$app, 'yii\web\Application') == true) {
            $get = Yii::$app->request->get();
            if (isset($get['clear']) && $get['clear'] == 'lang' && isset($get['key']) && $get['key'] == $key) {
                Cache::delete($cacheKey);
            }
        }
        if (isset($get['clear']) && $get['clear'] == 'lang') {
            $dataInCache = null;
        }elseif (isset($get['key']) && $get['key'] == $key)
        {
            $dataInCache = null;
        }
        else{
            $dataInCache = Cache::get($cacheKey);
        }

        if (!empty($dataInCache)) {
            return $dataInCache;
        }
        $dataInDb = static::getLanguageInDb($key, $languageId, $defaultValue);

        if (!empty($dataInDb)) {
            Cache::set($cacheKey, $dataInDb, 2592000);
            return $dataInDb;
        }
        Cache::set($cacheKey, $defaultValue, 3600);

        return $defaultValue;
    }

    public static function checkLanguageByKey($key, $languageId = null)
    {
        $website = new Website();
        if ($languageId == null) {
            $languageId = $website->getStoreData()->LanguageId;
        }
        $languageId = !empty($languageId) ? $languageId : 2;


        $cacheKey = 'i18n-l-' . $languageId . '-k-' . $key;
        $dataInCache = Cache::get($cacheKey);
        return !empty($dataInCache);
    }

}