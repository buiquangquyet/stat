<?php

namespace common\models\mysql\db;

use Yii;

/**
 * This is the model class for table "dictionary".
 *
 * @property int $id
 * @property string $word
 * @property string $pronunciation
 * @property string $mean
 * @property string $image
 * @property string $sentence
 * @property string $creat_time
 * @property string $send_time
 * @property int $user_id
 */
class Dictionary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sentence'], 'string'],
            [['creat_time', 'send_time'], 'safe'],
            [['user_id'], 'integer'],
            [['word', 'pronunciation', 'mean', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word' => 'Word',
            'pronunciation' => 'Pronunciation',
            'mean' => 'Mean',
            'image' => 'Image',
            'sentence' => 'Sentence',
            'creat_time' => 'Creat Time',
            'send_time' => 'Send Time',
            'user_id' => 'User ID',
        ];
    }
}
