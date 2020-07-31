<?php

namespace api\modules\v3\models;
use yii\helpers\ArrayHelper;
use common\models\News as NewsBase;
use api\modules\v3\models\User;
use common\models\NewsViews;

class News extends NewsBase {
    
    private $_user;
    private $_news_views = [];

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
        if ($this->_user) {
            $views_news = NewsViews::find()->where(['user_id' => $this->_user->id])->asArray()->all();
            $this->_news_views = ArrayHelper::getColumn($views_news, 'news_id');
        }
    }
    
    public function fields() {
        return [
            'id',
            'title', 
            'description', 
            'pub_date', 
            'image', 
            'link', 
            'rss' => function() {
                return $this->rss->rss_channel_name;
            },
            'is_read' => function() {
                return in_array($this->id, $this->_news_views) ? true : false;
            },
        ];
    }
    
    public function addViews() {
        if (!$this->_user) {
            return false;
        }
        $check = NewsViews::find()->where(['AND', ['user_id' => $this->_user->id], ['news_id' => $this->id]])->one();
        if (!$check) {
            $add_view = new NewsViews();
            $add_view->user_id = $this->_user->id;
            $add_view->news_id = $this->id;
            $add_view->save(false);
        }
        return true;
    }
    
    private function checkAuthUser() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->one();
            return $user;
        }
        return false;
    }

}
