<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 27/11/2018
 * Time: 14:47
 */

namespace common\models\mysql\modeldb;
use common\models\mysql\db\Dictionary as ParentDictionary;

class Dictionary extends ParentDictionary
{
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['user_id','word','mean'], 'required'],
            [['word', 'pronunciation', 'mean', 'image', 'sentence', 'creat_time', 'send_time'], 'safe'],
        ];
    }
}