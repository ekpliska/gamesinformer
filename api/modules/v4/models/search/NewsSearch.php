<?php

namespace api\modules\v4\models\search;
use api\modules\v4\models\NewsRead;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\modules\v4\models\News;
use api\modules\v4\models\RssChannel;


class NewsSearch extends News {

    public $title;
    public $rss_name;
    public $type;
    public $type_list;
    public $order_by;
    public $personal_news_ids = [];
    public $news_read_ids;

    public function __construct($config = array()) {
        $this->type_list = RssChannel::getTypesListApi();

        $news_list = new News();
        $news = $news_list->getPersonalNewsList();
        if ($news && isset($news['news']) && count($news['news'])) {
            $this->personal_news_ids = ArrayHelper::getColumn($news['news'], 'id');
        }

        // Лист прочитанных новостей текущего пользователя
        $news_read = new NewsRead();
        $this->news_read_ids = $news_read->getReadNewsIdsByUserId();
        parent::__construct($config);
    }

    public function rules() {
        return [
            [['title', 'rss_ids', 'type', 'order_by', 'day', 'read_flag'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {
        $rss_ids = [];
        $type = isset($params['type']) ? $params['type'] : null;
        $query = News::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['is_block' => 0]);

        // Исключаем персональные новости из списка основных новостей
        if (count($this->personal_news_ids)) {
            $query->andWhere(['NOT IN', 'id', $this->personal_news_ids]);
        }

        if (isset($params['order_by']) && $params['order_by'] == 'views') {
            $query->orderBy(['number_views' => SORT_DESC, 'pub_date' => SORT_DESC]);
        } else {
            $query->orderBy(['pub_date' => SORT_DESC]);
        }

        if (isset($params['day']) && $params['day'] === 'current') {
            $current_date = new \DateTime('NOW');
            $query
                ->andWhere(['between', 'pub_date', $current_date->format('Y-m-d 00:00:00'), $current_date->format('Y-m-d 23:59:59')]);
        }
        
        if (in_array($type, $this->type_list)) {
            $query->joinWith('rss')->where(['type' => array_search($type, $this->type_list)]);
        }
        
        if (isset($params['title'])) {
            $query->andWhere(['like', 'title', $params['title']]);
        }

        if (isset($params['rss_ids'])) {
            $rss_ids = $this->checkRssIds($params['rss_ids'], $type);
            $query->andWhere(['in', 'rss_channel_id', $rss_ids]);
        }

        // Скрыть прочитанные новости
        if (isset($params['read_flag']) && $params['read_flag'] == 'hide') {
            $query->andWhere(['NOT IN', 'id', $this->news_read_ids]);
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
