<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 30/10/2018
 * Time: 08:47
 */

namespace common\models\mysql\modeldb;
use common\components\Cache;
use common\models\mysql\db\User as ParentUser;

class User extends ParentUser
{
    public static function GetNameById($id=1){
        $key = \Yii::$app->params['CACHE_USER_NAME_BY_ID_'].$id;
        $rs = Cache::get($key);
        if(empty($rs)){
            $rs = User::findOne($id);
            $rs = $rs->username;
            $rs = Cache::set($key,$rs,60*60);
        }
        return $rs;
    }
}