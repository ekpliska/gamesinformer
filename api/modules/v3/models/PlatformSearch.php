<?php

namespace api\modules\v3\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v3\models\Platform;

class PlatformSearch extends Platform {
    
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
        
        $name_platform = isset($params['name']) ? $params['name'] : null;
        
        $query = Platform::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        $this->load($params);
        
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['name_platform' => SORT_ASC]);

        if ($name_platform) {
            $query->where(['name_platform' => $name_platform]);
        }
        
        return $dataProvider;
    }

}
