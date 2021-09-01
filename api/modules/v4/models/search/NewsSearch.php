<?php

namespace api\modules\v4\models\search;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use api\modules\v4\models\News;
use api\modules\v4\models\RssChannel;


class NewsSearch extends News {

    public $title;
    public $rss_name;
    public $type;
    public $type_list;
    public $order_by;

    public function __construct($config = array()) {
        $this->type_list = RssChannel::getTypesListApi();
        parent::__construct($config);
    }

    public function rules() {
        return [
            [['title', 'rss_ids', 'type', 'order_by'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        
        $rss_ids = [];
        $type = isset($params['type']) ? $params['type'] : null;
        
        $query = News::find()
            ->select(['`news`.*', 'COUNT(`news_likes`.`news_id`) AS `likes_count`'])
            ->joinWith('likes')
            ->groupBy('`news`.`id`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['is_block' => 0]);

        if (isset($params['order_by']) && $params['order_by'] == 'likes') {
            $query->orderBy(['likes_count' => SORT_DESC, 'pub_date' => SORT_DESC]);
        } else {
            $query->orderBy(['pub_date' => SORT_DESC]);
        }
        
        
        if (in_array($type, $this->type_list)) {
            $query->joinWith('rss')->where(['type' => array_search($type, $this->type_list)]);
        } else {
            return $dataProvider;
        }
        
        if (isset($params['title'])) {
            $query->where(['like', 'title', $params['title']]);
        }

        if (isset($params['rss_ids'])) {
            $rss_ids = $this->checkRssIds($params['rss_ids'], $type);
            $query->where(['in', 'rss_channel_id', $rss_ids]);
        }
        
        return $dataProvider;
    }
    
    private function checkRssIds($params, $type) {
        $rss = [];
        $rss_ids = [];
        $rss_names = [];
        if (count($params) > 0) {
            $ids_arr = explode(',', $params);
            $rss = RssChannel::find()
                ->where(['IN', 'id', $ids_arr])
                ->andWhere(['type' => array_search($type, $this->type_list)])
                ->asArray()
                ->all();
            $rss_ids = ArrayHelper::getColumn($rss, 'id');
        }
        return $rss_ids;
    }

}
