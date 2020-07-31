<?php

namespace api\modules\v3\models\search;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use api\modules\v3\models\News;
use api\modules\v3\models\RssChannel;

class NewsSearch extends News {

    public $title;
    public $rss_name;

    public function rules() {
        return [
            [['title', 'rss_ids'], 'safe'],
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

        if (isset($params['rss_ids'])) {
            $rss_ids = $this->checkRssIds(Html::encode($params['rss_ids']));
            $query->where(['in', 'rss_channel_id', $rss_ids]);
        }
        
        return $dataProvider;
    }
    
    private function checkRssIds($params) {
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
