<?php

namespace api\modules\v4\models;
use common\models\FavoriteSeries;
use yii\helpers\ArrayHelper;
use common\models\News as NewsBase;
use common\models\NewsViews;
use common\models\NewsRead;
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
            'is_read' => function() {
                if (!$this->_user) {
                    return null;
                }
                return $this->checkNewsReadByUserId($this->_user->id);
            },
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

        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $user_id = $this->_user->id;
        $logout_date = new \DateTime($this->_user->logout_at, new \DateTimeZone('Europe/Moscow'));

        $favourite_game_ids = Favorite::getGamesByUserId($user_id);
        $favourite_game_ids_by_series = FavoriteSeries::getGamesByUserId($user_id);

        // IDs избранных игр и серий
        $game_ids = ArrayHelper::merge($favourite_game_ids, $favourite_game_ids_by_series);

        if (count($game_ids)) {
            return [
                'success' => true,
                'news' => [],
            ];
        }

        // Теги Игр по Избранным играм
        $tags_link_game = TagLink::getTagsByGameIds($game_ids);
        $tags_link_game_ids = ArrayHelper::getColumn($tags_link_game, 'tag_id');

        // Теги Новостей по Избранным играм
        $tags_link_news = TagLink::getNewsByTags($tags_link_game_ids);
        $tags_link_news_ids = ArrayHelper::getColumn($tags_link_news, 'type_uid');

        // Новости по избранным играм за диапазон [дата закрытия приложения; текущая дата]
        $news = News::find()
            ->where(['in', 'id', $tags_link_news_ids])
            ->andWhere([
                'between',
                'pub_date',
                $logout_date->format('Y-m-d H:i:s'),
                $current_date->format('Y-m-d 23:59:59')
            ])
            ->orderBy(['pub_date' => SORT_DESC])
            ->all();

        return [
            'success' => true,
            'news' => $news,
        ];
    }

    /**
     * Автопрочтение новостей для текущего пользователя
     * @param $news_ids array
     * @return bool|void
     */
    public function autoRead($news_ids) {

        if (!$this->_user) {
            return false;
        }

        $ids = explode(',', $news_ids);

        if (!count($ids)) {
            return false;
        }

        $news_read = NewsRead::find()->where(['in', 'news_id', $ids])->all();
        $news = News::find()->where(['in', 'id', $ids])->all();

        if (!count($news_read) && count($news)) {
            return NewsRead::createAutoRead($news, $this->_user->id);
        }

        if (count($news_read) && count($news)) {
            foreach ($news_read as $item) {
                if (!$item->updateAutoRead($item, $this->_user->id)) {
                    continue;
                }
            }
            return true;
        }

        return false;

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
