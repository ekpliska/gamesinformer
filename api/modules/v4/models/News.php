<?php

namespace api\modules\v4\models;
use yii\helpers\ArrayHelper;
use common\models\News as NewsBase;
use common\models\NewsViews;
use common\models\NewsLikes;
use common\models\RssChannel;
use common\models\Favorite;
use common\models\TagLink;

class News extends NewsBase {
    
    private $_user;
    private $_news_likes = [];

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
        if ($this->_user) {
            $likes_news = NewsLikes::find()->where(['user_id' => $this->_user->id])->asArray()->all();
            $this->_news_likes = ArrayHelper::getColumn($likes_news, 'news_id');
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
            'is_youtube_news' => function() {
                return ($this->rss->type == RssChannel::TYPE_YOUTUBE) ? true : false;
            },
            'tags' => function() {
                return $this->getNewseTagsList();
            },
            'number_views',
        ];
    }

    public function addViews() {
        $session = \Yii::$app->session;
        if (!isset($session['news_view'])) {
            $session->set('news_view', [$this->id]);
            $this->updateCounters(['number_views' => 1]);
        }
    }

    /**
     * Метод добавления просмотров только для зарегистрированных пользователей
     * Метод не используется
     */
    public function addViewsHide() {
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
    
    public function like() {
        if (!$this->_user) {
            return false;
        }
        
        $user_like = NewsLikes::find()->where(['AND', ['user_id' => $this->_user->id], ['news_id' => $this->id]])->one();

        if ($user_like) {
            return $user_like->delete() ? true : false;
        }
        
        $add_like = new NewsLikes();
        $add_like->user_id = $this->_user->id;
        $add_like->news_id = $this->id;
        return $add_like->save() ? true : false;
    }

    /**
     * В Центре внимания
     */
    public function getPersonalNewsList() {
        if (!$this->_user) {
            \Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'news' => [],
                'error' => ['Неавторизованный пользователь'],
            ];
        }

        // Избранные игры текущего пользователя
        $favorite_games = Favorite::find()->where(['user_uid' => $this->_user->id])->all();
        if (!$favorite_games) {
            return [
                'success' => true,
                'news' => [],
            ];
        }

        $favorite_games_ids = ArrayHelper::getColumn($favorite_games, 'game_id');

        // Теги Игр по Избранным играм
        $tags_link_game = TagLink::getTagsByGameIds($favorite_games_ids);
        $tags_link_game_ids = ArrayHelper::getColumn($tags_link_game, 'tag_id');

        // Теги Новостей по Избранным играм
        $tags_link_news = TagLink::getNewsByTags($tags_link_game_ids);
        $tags_link_news_ids = ArrayHelper::getColumn($tags_link_news, 'type_uid');

        $current_date = new \DateTime('NOW');
        // Новости за текущий день по избранным играм
        $news = News::find()
            ->where(['in', 'id', $tags_link_news_ids])
            ->andWhere(['between', 'pub_date', $current_date->format('Y-m-d 00:00:00'), $current_date->format('Y-m-d 23:59:59')])
            ->orderBy(['number_views' => SORT_DESC])
            ->all();

        return [
            'success' => true, 
            'news' => $news,
        ];
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
