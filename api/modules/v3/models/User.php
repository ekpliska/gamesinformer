<?php

namespace api\modules\v3\models;
use yii\helpers\Url;
use common\models\User as UserBase;
use api\modules\v3\models\Series;

class User extends UserBase {

    public function fields() {

        return [
            'id',
            'username',
            'email',
            'photo',
            'created_at',
            'updated_at',
            'platforms' => function() {
                $platforms = $this->userPlatforms;
                $result = [];
                if ($platforms) {
                    foreach ($platforms as $platform) {
                        $result[] = [
                            'id' => $platform->platform_id,
                            'name' => $platform->platform->name_platform,
                            'logo_path' => $platform->platform->logo_path
                        ];
                    }
                }
                return $result;
            },
            'is_time_alert'function() {
                return $this->is_time_alert ? true : false;
            },
            'time_alert',
            'days_of_week' => function() {
                return json_decode($this->days_of_week);
            },
            'aaa_notifications' => function() {
                return $this->aaa_notifications ? true : false;
            },
            'is_shares' => function() {
                return $this->is_shares ? true : false;
            },
            'is_advertising' => function() {
                return $this->is_advertising ? true : false;
            },
        ];
    }

    public function userFavoriteGames() {
        $favorites = $this->userFavorite;
        $result = [];
        if ($favorites) {
            foreach ($favorites as $favorite) {
                $game = Game::find()->where(['id' => $favorite->game_id])->one();
                $cover = Url::home(true) . ltrim($game->cover, '/');
                if (strpos($game->cover, 'youtube.com')) {
                    $cover = $game->cover;
                }
                $result[] = [
                    'id' => $game->id,
                    'title' => $game->title,
                    'description' => $game->description,
                    'series' => $game->getGameSeries(),
                    'release_date' => $game->release_date,
                    'publish_at' => $game->publish_at,
                    'published' => $game->published,
                    'website' => $game->website,
                    'youtube' => $game->youtube,
                    'youtube_btnlink' => $game->youtube_btnlink,
                    'twitch' => $game->twitch,
                    'cover' => $cover,
                    'gameGenres' => $game->getGameGenresList(),
                    'gamePlatformReleases' => $game->getGamePlatformReleasesList(),
                    'is_favorite' => true,
                    'comments' => $game->getCommentsList(),
                    'is_aaa' => $game->is_aaa,
                ];
            }
        }
        return $result;
    }
    
    public function userFavoriteSeriesList() {
        $favorites = $this->userFavoriteSeries;
        $result = [];
        if ($favorites) {
            foreach ($favorites as $favorite) {
                $series = Series::find()->where(['id' => $favorite->series_id])->one();
                $result[] = $series;
            }
        }
        return $result;
    }

}
