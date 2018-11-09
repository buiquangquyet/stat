<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 11:30
 */

namespace common\models\mysql\modeldb;
use common\components\Cache;
use common\models\mysql\db\Course as ParentCourse;
use Yii;
class Course extends ParentCourse
{
    /**
     * @param bool $cache
     */
    public function GetAll($cache=true){
        $key = \Yii::$app->params['CACHE_COURSE_ALL'];
        $data = Cache::get($key);
        if(empty($data) || $cache == false){
            $data = Self::findAll();
        }
        return $data;
    }

    /**
     * @param      $id
     * @param bool $cache
     */
    public function GetById($id, $cache=true){
        $key = \Yii::$app->params['CACHE_COURSE_BY_ID_'].$id;
        $data = Cache::get($key);
        if(empty($data) || $cache == false){
            $data = Self::findOne($id);
        }
        return $data;
    }


    /**
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->id;
        $arrayKey [] = Yii::$app->params['CACHE_COURSE_ALL'];
        $arrayKey [] = Yii::$app->params['CACHE_COURSE_BY_ID_'];
        if (!empty($arrayKey)) {
            foreach ($arrayKey as $key) {
                Cache::delete($key . $this->id);
                Cache::delete($key.'*');
            }
        }


    }

}