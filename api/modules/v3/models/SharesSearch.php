<?php

namespace api\modules\v3\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v3\models\Shares;

class SharesSearch extends Shares {
    
    public $type;

    public function rules() {
        return [
            [['type'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        
        $type_id = isset($params['type']) ? $params['type'] : null;
        
        $query = Shares::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        
        $this->load($params);
        
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['date' => SORT_DESC])->orderBy(['type_share' => SORT_ASC]);

        if ($type_id && array_key_exists($type_id, $this->getTypeList())) {
            $query->where(['type_share' => (int)$params['type']])->orderBy(['date' => SORT_DESC]);
        }
        
        return $dataProvider;
    }

}
