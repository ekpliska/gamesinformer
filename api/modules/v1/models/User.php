<?php

namespace api\modules\v1\models;

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
            'favorite' => function() {
                $favorites = $this->userFavorite;
                $result = [];
                if ($favorites) {
                    foreach ($favorites as $favorite) {
                        $game = Game::find()->where(['id' => $favorite->game_id])->one();
                        $result[] = [
                            'id' => $game->id,
                            'title' => $game->title,
                            'series' => $game->series,
                            'release_date' => $game->release_date,
                            'publish_at' => $game->publish_at,
                            'published' => $game->published,
                            'website' => $game->website,
                            'youtube' => $game->youtube,
                            'youtube_btnlink' => $game->youtube_btnlink,
                            'twitch' => $game->twitch,
                            'cover' => $game->cover,
                            'gameGenres' => $this->gameGenres($game),
                            'gamePlatformReleases' => $this->gamePlatforms($game),
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

}
