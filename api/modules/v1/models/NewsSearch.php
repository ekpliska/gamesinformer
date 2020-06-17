<?php

namespace api\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use api\modules\v1\models\News;
use common\models\NewsViews;

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

    public function search($params, $user_id = null) {
        
        if ($user_id) {
            $views_news = NewsViews::find()->where(['user_id' => $user_id])->asArray()->all();
            $news_ids = ArrayHelper::getColumn($views_news, 'news_id');
        }
        
        $query = News::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['NOT IN', 'id', $news_ids])->orderBy(['pub_date' => SORT_DESC]);
                
        if (isset($params['rss_id'])) {
            $query->andFilterWhere(['rss_channel_id' => $params['rss_id']]);
        }
        
        return $dataProvider;
    }

}
