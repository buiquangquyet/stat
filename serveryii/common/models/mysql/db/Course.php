<?php

namespace common\models\mysql\db;

use Yii;

/**
 * This is the model class for table "course".
 *
 * @property int $id
 * @property string $name
 *
 * @property Lession[] $lessions
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessions()
    {
        return $this->hasMany(Lession::className(), ['course_id' => 'id']);
    }
}
