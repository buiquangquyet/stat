<?php

namespace common\models\mysql\modeldb;

use common\components\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\mysql\db\News;

/**
 * NewsRewrite represents the model behind the search form of `common\models\mysql\db\News`.
 */
class NewsRewrite extends News
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'categoryId', 'userId', 'published', 'order', 'storeId', 'type', 'languageId', 'originId'], 'integer'],
            [['image', 'name', 'description', 'content', 'createDate', 'updateDate', 'createEmail', 'updateEmail'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = News::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'categoryId' => $this->categoryId,
            'userId' => $this->userId,
            'published' => $this->published,
            'order' => $this->order,
            'createDate' => $this->createDate,
            'updateDate' => $this->updateDate,
            'storeId' => $this->storeId,
            'type' => $this->type,
            'languageId' => $this->languageId,
            'originId' => $this->originId,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'createEmail', $this->createEmail])
            ->andFilterWhere(['like', 'updateEmail', $this->updateEmail]);

        return $dataProvider;
    }

    /**
     * @param bool  $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->id;
        $arrayKey [] = 'CACHE_VIEW_NEWS_';
        if (!empty($arrayKey)) {
            foreach ($arrayKey as $key) {
                Cache::delete($key . $this->id);
                Cache::delete($key);
            }
        }


    }
}
