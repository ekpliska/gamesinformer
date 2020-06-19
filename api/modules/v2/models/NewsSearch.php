<?php

namespace api\modules\v2\models;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use api\modules\v2\models\News;

class NewsSearch extends News {

    public $title;
    public $rss_name;

    public function rules() {
        return [
            [['title', 'rss_name'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        
        $rss_ids = [];
        
        $query = News::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->orderBy(['pub_date' => SORT_DESC]);

        if (isset($params['rss_name'])) {
            $rss_ids = $this->getRssIds(Html::encode($params['rss_name']));
            $query->where(['in', 'rss_channel_id', $rss_ids]);
        }
        
        return $dataProvider;
    }
    
    private function getRssIds($params) {
        $rss = [];
        $rss_ids = [];
        $rss_names = [];
        if (count($params) > 0) {
            $rss_names = explode(',', $params);
            $rss = RssChannel::find()->where(['IN', 'rss_channel_name', $rss_names])->asArray()->all();
            $rss_ids = ArrayHelper::getColumn($rss, 'id');
        }
        return $rss_ids;
    }

}
