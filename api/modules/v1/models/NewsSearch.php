<?php

namespace api\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\News;

class NewsSearch extends News {

    public $title;
    public $rss_id;

    public function rules() {
        return [
            [['title', 'rss_id'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {

        $query = News::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['pub_date' => SORT_DESC]);
                
        if (isset($params['rss_id'])) {
            $query->andFilterWhere(['rss_channel_id' => $params['rss_id']]);
        }
        
        return $dataProvider;
    }

}
