<?php

namespace api\modules\v2\models;
use common\models\Series as SeriesBase;
use yii\helpers\Url;
use common\models\User;
use common\models\Favorite;

class Series extends SeriesBase {
    
    public function fields() {
        
        return parent::fields() + [
            'games' => function() {
                $games = $this->gameSeries;
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
                            'series' => $item->game->series,
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
        return $result;
    }
    
    private function isFavorite() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->asArray()->one();
            if (!$user) {
                return false;
            }
            $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => $user['id']], ['game_id' => $this->id]])->asArray()->one();
            if ($favorite) {
                return true;
            }
        }
        return false;
    }
    
}
