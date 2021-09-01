<?php

namespace api\modules\v4\models\search;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v4\models\Series;

class SeriesSearch extends Series {

    public $games;

    public function rules() {
        return [
            [['series_name'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {

        $query = Series::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['enabled' => 1])->orderBy(['id' => SORT_DESC]);

        if (isset($params['series_name'])) {
            $query->andFilterWhere(['like', 'series_name', $params['series_name']]);
        }

        return $dataProvider;
    }

}
