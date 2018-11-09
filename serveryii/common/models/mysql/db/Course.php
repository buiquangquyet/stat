<?php

namespace common\models\mysql\db;

use Yii;

/**
 * This is the model class for table "course".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $image
 * @property string $rate
 * @property string $price
 * @property string $created_time
 * @property string $updated_time
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
            [['description'], 'string'],
            [['rate'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['price'], 'string', 'max' => 10],
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
            'description' => 'Description',
            'image' => 'Image',
            'rate' => 'Rate',
            'price' => 'Price',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
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
