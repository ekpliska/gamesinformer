<?php

namespace frontend\models\searchFrom;
use yii\data\ActiveDataProvider;
use common\models\Genre;

class GenreSearch extends Genre {
 
    public function rules() {
        return [
            [['name_genre'], 'string', 'max' => 70]
        ];
    }

    public function search($params) {
        $query = Genre::find()->groupBy('id');
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
        $query->orderBy(['isRelevant' => SORT_DESC]);
        $query->andFilterWhere([
            'name_genre' => $this->name_genre,
        ]);
        return $dataProvider;
    }
}
