<?php

namespace api\modules\v3\models;
use common\models\Platform as PlatformBase;

class Platform extends PlatformBase {

    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
    }
    
    public function fields() {
        
        return [
            'id',
            'name' => function() {
                return $this->name_platform;
            },
            'logo_path',
            'description',
            'youtube',
            'cover' => function() {
                if (strpos($this->cover, 'youtube.com')) {
                    return $this->cover;
                }
                return Url::home(true) . ltrim($this->cover, '/');
            },
            'is_relevant' => function() {
                return $this->isRelevant;
            },
            'top_games' => function() {
                $games = $this->topGames;
                $result = [];
                if ($games) {
                    foreach ($games as $item) {
                        $cover = Url::home(true) . ltrim($item->game->cover, '/');
                        if (strpos($item->game->cover, 'youtube.com')) {
                            $cover = $item->game->cover;
                        }
                        $result[] = [
                            'id' => $item->game->id,
                            'title' => $item->game->title,
                            'description' => $item->game->description,
                            'release_date' => $item->game->release_date,
                            'publish_at' => $item->game->publish_at,
                            'published' => $item->game->published,
                            'website' => $item->game->website,
                            'youtube' => $item->game->youtube,
                            'youtube_btnlink' => $item->game->youtube_btnlink,
                            'twitch' => $item->game->twitch,
                            'cover' => $cover,
                            'gameGenres' => $this->gameGenres($item->game),
                            'gamePlatformReleases' => $this->gamePlatforms($item->game),
                            'is_favorite' => $this->isFavorite(),
                        ];
                    }
                }
                usort($result, function($value_f, $value_s) {
                    if (strtotime($value_f['release_date']) == strtotime($value_s['release_date'])) {
                        return 0;
                    }
                    return (strtotime($value_f['release_date']) > strtotime($value_s['release_date'])) ? -1 : 1;
                });
                return $result;
            },
        ];
    }

    private function gameGenres($game) {
        $geners = $game->gameGenres;
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

    private function gamePlatforms($game) {
        $platforms = $game->gamePlatformReleases;
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
    
    private function isFavorite() {
        if (!$this->_user) {
            return false;
        }

        $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => $user['id']], ['game_id' => $this->id]])->asArray()->one();
        if ($favorite) {
            return true;
        }
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
