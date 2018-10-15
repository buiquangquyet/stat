<?php

namespace common\models\mysql\modeldb;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\mysql\db\Lession;

/**
 * LessionRewrite represents the model behind the search form of `common\models\mysql\db\Lession`.
 */
class LessionRewrite extends Lession
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course_id'], 'integer'],
            [['name'], 'unique'],
            [['alias', 'description', 'link_video', 'content', 'sugget_vocabulary'], 'safe'],
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
        $query = Lession::find();

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
            'course_id' => $this->course_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'link_video', $this->link_video])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'sugget_vocabulary', $this->sugget_vocabulary]);

        return $dataProvider;
    }
}
