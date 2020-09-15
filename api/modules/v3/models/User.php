<?php

namespace api\modules\v3\models;
use yii\helpers\Url;
use common\models\User as UserBase;

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
            'is_time_alert',
            'time_alert',
            'days_of_week' => function() {
                return json_decode($this->days_of_week);
            },
            'aaa_notifications',
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
                    'is_aaa' => $game->game->is_aaa,
                ];
            }
        }
        return $result;
    }

}
