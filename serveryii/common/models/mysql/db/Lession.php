<?php

namespace common\models\mysql\db;

use Yii;

/**
 * This is the model class for table "lession".
 *
 * @property int $id
 * @property string $name
 * @property int $course_id
 * @property string $alias
 * @property string $description
 * @property string $link_video
 * @property string $content
 * @property string $sugget_vocabulary
 *
 * @property Course $course
 */
class Lession extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lession';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id'], 'integer'],
            [['description', 'content'], 'string'],
            [['name', 'alias', 'link_video', 'sugget_vocabulary'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
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
            'course_id' => 'Course ID',
            'alias' => 'Alias',
            'description' => 'Description',
            'link_video' => 'Link Video',
            'content' => 'Content',
            'sugget_vocabulary' => 'Sugget Vocabulary',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
