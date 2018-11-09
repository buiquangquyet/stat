<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 13:26
 */

namespace common\models\mysql\modeldb;

use common\components\Cache;
use common\models\mysql\db\Lession as ParentLession;
use Yii;

class Lession extends ParentLession
{
    /**
     * @param bool $cache
     */
    public function GetAll($cache = true)
    {
        $key = \Yii::$app->params['CACHE_LESSION_ALL'];
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
    public function GetById($id, $cache = true)
    {

        $key = \Yii::$app->params['CACHE_LESSION_BY_ID_'] . $id;
        $data = Cache::get($key);
        if(empty($data) || $cache == false){
            $data = Self::findOne($id);
        }
        return $data;
    }


    public function GetListByCourseId($id, $cache = false){
        $key = \Yii::$app->params['CACHE_LESSION_BY_COURSE_ID_'] . $id;
        $data = Cache::get($key);
        if(empty($data) || $cache == false){
            $data = Self::find()->where(['course_id'=>$id])->all();
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
        $arrayKey [] = Yii::$app->params['CACHE_LESSION_ALL'];
        $arrayKey [] = Yii::$app->params['CACHE_LESSION_BY_ID_'] ;
        $arrayKey [] = Yii::$app->params['CACHE_LESSION_BY_COURSE_ID_'] ;
        if (!empty($arrayKey)) {
            foreach ($arrayKey as $key) {
                Cache::delete($key . $this->id);
                Cache::delete($key.'*');
            }
        }


    }

}