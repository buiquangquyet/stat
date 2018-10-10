<?php
/**
 * Created by PhpStorm.
 * User: ducquan
 * Date: 12/10/2015
 * Time: 16:33 PM
 */

namespace common\components;


use Yii;
use yii\helpers\Json;

class CacheClient
{
    public static $data;
    public static $keyword;
    public static $cache;
    public static $time = 1200;

    /**
     *
     * @param type $keyword
     * @return boolean
     */
    public static function startCache($keyword)
    {
        //Create cache key
        self::$keyword = md5(is_object($keyword) || is_array($keyword) ? json_encode($keyword) : strtolower($keyword));
        self::$data = Yii::$app->cache->get(self::$keyword);
        if (true == self::$cache && self::$data !== false) {
            self::$data = json_decode(self::$data);
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $data
     */
    public static function endCache($data)
    {
        self::$data = $data;
        if (!empty(self::$keyword) && true == self::$cache) {
            Yii::$app->cache->set(self::$keyword, json_encode($data), self::$time);
            self::$keyword = null;
        }
    }

    public static function get($keyword, $default = false)
    {
        $cache = \Yii::$app->params['cache'];
        if (!$cache['enable']) {
            return $default;
        }
        $data = \Yii::$app->cache->get(self::identity($keyword));
        if ($data != false) {
            return json_decode($data, false);
            //return Json::decode($data, false);
        }
        return $default;
    }

    public static function del($keyword)
    {
        return \Yii::$app->cache->delete(self::identity($keyword));
    }

    public static function set($keyword, $object, $duration = null)
    {
        $cache = \Yii::$app->params['cache'];
        if (!$cache['enable']) {
            return false;
        }
        if ($duration == null) {
            $duration = $cache['duration'];
        }
//        if ($object == null) {
//            return false;
//        }
        return \Yii::$app->cache->set(self::identity($keyword), Json::encode($object), $duration);
    }

    public static function identity($keyword)
    {
        if (filter_var($keyword, FILTER_VALIDATE_URL)) {
            $keyword = self::identityURL($keyword);
        } else if (is_array($keyword)) {
            asort($keyword);
        } else if (is_object($keyword)) {
            $keyword = get_object_vars($keyword);
            arsort($keyword);
        }
        return md5(is_object($keyword) || is_array($keyword) ? json_encode($keyword) : strtolower($keyword));
    }

    public static function identityURL($keyword)
    {
        $data = parse_url($keyword);
        if (isset($data['query'])) {
            $output = [];
            parse_str($data['query'], $output);
            asort($output);
            $data['query'] = http_build_query($output);
        }
        $output = [];
        parse_str(isset($data['query']) ? $data['query'] : '', $output);
        asort($output);
        $tmpQuery = [];
        foreach ($output as $key => $value) {
            $tmpQuery[] = $key . '=' . $value;
        }
        $query = implode('&', $tmpQuery);
        $scheme = !empty($data['scheme']) ? $data['scheme'] : '';
        $host = !empty($data['host']) ? $data['host'] : '';
        $path = !empty($data['path']) ? $data['path'] : '';
        $finalQuery = $scheme . '://' . $host . $path . '?' . $query;
        return $finalQuery;
    }
}