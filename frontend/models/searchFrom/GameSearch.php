<?php

namespace frontend\models\searchFrom;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Game;

/**
 * GameSearch represents the model behind the search form of `common\models\Game`.
 */
class GameSearch extends Game {
 
    public function rules() {
        return [
            [['id', 'published'], 'integer'],
            [['title', 'description', 'series', 'release_date', 'publish_at', 'cover', 'website', 'youtube', 'youtube_btnlink', 'twitch', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios() {
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
    public function search($params) {
        $query = Game::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['published' => SORT_ASC]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'release_date' => $this->release_date,
            'publish_at' => $this->publish_at,
            'published' => $this->published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'series', $this->series])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'youtube', $this->youtube])
            ->andFilterWhere(['like', 'youtube_btnlink', $this->youtube_btnlink])
            ->andFilterWhere(['like', 'twitch', $this->twitch]);

        return $dataProvider;
    }
}
