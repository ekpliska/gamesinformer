<?php

namespace api\modules\v4\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v4\models\Genre;

class GenreSearch extends Genre {
    
    public $name;

    public function rules() {
        return [
            [['name'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        
        $name_genre = isset($params['name']) ? $params['name'] : null;
        
        $query = Genre::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        $this->load($params);
        
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['name_genre' => SORT_ASC]);

        if ($name_genre) {
            $query->where(['name_genre' => $name_genre]);
        }
        
        return $dataProvider;
    }

}
