<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 5/22/17
 * Time: 11:41 AM
 */

namespace common\components;


use Yii;
use yii\caching\ApcCache;

class ApcCacheClient
{
    static private $hasApc = null;
    static private $apcClient = null;

    /**
     * Get APC cache client, tự tạo nếu không có, tự trả về cache client mặc định nếu không enable apc, apcu extension
     * @return \yii\caching\Cache the cache application component. Null if the component is not enabled.
     */
    static public function getCache()
    {
        if (static::$hasApc == null) {
            static::$hasApc = \Yii::$app->has('apccache');
        }
        if (extension_loaded('apcu') || extension_loaded('apc')) {
            if (static::$hasApc) {
                return Yii::$app->get('apccache');
            } else {
                if (static::$apcClient == null) {
                    static::$apcClient = new ApcCache(['useApcu' => extension_loaded('apcu')]);
                }
                return static::$apcClient;
            }
        }
        return Yii::$app->getCache();
    }

}