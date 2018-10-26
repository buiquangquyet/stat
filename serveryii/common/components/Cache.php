<?php
/**
 * Created by PhpStorm.
 * User: Panda
 * Date: 12/20/2017
 * Time: 9:16 AM
 */

namespace common\components;


use Yii;

class Cache
{
    public static function set($keyword, $data, $duration = 60)
    {
        $keyword = self::buildKey($keyword);
        Yii::$app->cache->set($keyword, $data, $duration);
    }

    public static function get($keyword)
    {
        $keyword = self::buildKey($keyword);
        if (is_a(Yii::$app, 'yii\web\Application') == true) {
            $get = Yii::$app->request->get();
            $nocache = (isset($get['clear']) && $get['clear'] == 'yess') ? true : false;
            if ($nocache) {
                 Yii::$app->cache->delete($keyword);
            }
        }
        return Yii::$app->cache->get($keyword);
    }

    public static function delete($keyword)
    {
        $keyword = self::buildKey($keyword);
        Yii::$app->cache->delete($keyword);
    }

    public static function buildKey($key)
    {
        return ($key);
        //return Yii::$app->cache->buildKey($key);
    }
}