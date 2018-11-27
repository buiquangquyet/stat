<?php

namespace common\models\mysql\modeldb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DictionaryRewrite represents the model behind the search form of `common\models\mysql\db\Dictionary`.
 */
class DictionaryRewrite extends Dictionary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['word', 'pronunciation', 'mean', 'image', 'sentence', 'creat_time', 'send_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Dictionary::find();

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
            'creat_time' => $this->creat_time,
            'send_time' => $this->send_time,
            'user_id' => $this->user_id,
        ]);
        $query->andFilterWhere(['like', 'word', $this->word])
            ->andFilterWhere(['like', 'pronunciation', $this->pronunciation])
            ->andFilterWhere(['like', 'mean', $this->mean])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'sentence', $this->sentence]);
        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }
}
