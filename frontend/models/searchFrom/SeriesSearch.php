<?php

namespace frontend\models\searchFrom;
use yii\data\ActiveDataProvider;
use common\models\Series;

class SeriesSearch extends Series {
 
    public function rules() {
        return [
            [['series_name'], 'string', 'max' => 70],
            [['enabled'], 'integer'],
        ];
    }

    public function search($params) {
        $query = Series::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->orderBy(['enabled' => SORT_DESC]);
        $query->andFilterWhere([
            'series_name' => $this->series_name,
        ]);
        return $dataProvider;
    }
}
