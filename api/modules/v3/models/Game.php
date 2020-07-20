<?php

namespace api\modules\v3\models;
use yii\helpers\Url;
use common\models\Game as GameBase;
use api\modules\v3\models\User;
use common\models\Favorite;

class Game extends GameBase {

    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
    }
    
    public function fields() {
        
        return [
            'id', 'title', 'description', 
            'release_date', 
            'publish_at', 'published', 
            'website', 'youtube', 'youtube_btnlink', 'twitch',
            'cover' => function() {
                if (strpos($this->cover, 'youtube.com')) {
                    return $this->cover;
                }
                return Url::home(true) . ltrim($this->cover, '/');
            },
            'series' => function() {                
                return $this->getGameSeries();
            },
            'gameGenres' => function() {
                return $this->getGameGenres();
            },
            'gamePlatformReleases' => function() {
                return $rhis->getGamePlatformReleases();
            },
            'is_favorite' => function() {
                return $this->isFavorite;
            },
        ];
    }

    public function getGamePlatformReleases () {
        $platforms = $this->gamePlatformReleases;
        $result = [];
        if ($platforms) {
            foreach ($platforms as $platform) {
                $result[] = [
                    'id' => $platform->platform_id,
                    'name' => $platform->platform->name_platform,
                    'date_platform_release' => $platform->date_platform_release,
                    'logo_path' => $platform->platform->logo_path,
                ];
            }
        }
        usort($result, function($value_f, $value_s) {
            if (strtotime($value_f['date_platform_release']) == strtotime($value_s['date_platform_release'])) {
                return 0;
            }
            return (strtotime($value_f['date_platform_release']) > strtotime($value_s['date_platform_release'])) ? -1 : 1;
        });
        return $result;
    }

    public function getGameGenres() {
        $geners = $this->gameGenres;
        $result = [];
        if ($geners) {
            foreach ($geners as $gener) {
                $result[] = [
                    'id' => $gener->genre->id,
                    'name' => $gener->genre->name_genre
                ];
            }
        }
        return $result;
    }

    public function getGameSeries() {
        $series = $this->seriesGame;
        $result = [];
        if ($series) {
            foreach ($series as $item) {
                $result[] = [
                    'id' => $item->series_id,
                    'series_name' => $item->series->series_name,
                    'description' => $item->series->description,
                    'image' => $item->series->image,
                ];
            }
        }
        return $result;
    }

    public function getIsFavorite() {
        if (empty($this->_user)) {
            return false;
        }

        $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => $this->_user->id], ['game_id' => $this->id]])->asArray()->one();
        if (!$favorite) {
            return false;
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
