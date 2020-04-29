<?php

namespace api\modules\v1\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Game;

class GameSearch extends Game {
 
    public function rules() {
        return [
            [[
                'title', 'series'
            ], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        
        $query = Game::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['published' => true])->orderBy(['release_date' => SORT_ASC]);
        
        if (isset($params['title'])) {
            $query->andFilterWhere(['like', 'title', $params['title']]);
        }

        if (isset($params['series'])) {
            $query->andFilterWhere(['like', 'series', $params['series']]);
        }
        
        return $dataProvider;
    }
}
