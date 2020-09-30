<?php

namespace api\modules\v3\models;
use yii\helpers\Url;
use common\models\Platform as PlatformBase;

class Platform extends PlatformBase {

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
                            'series'=> $item->game->getGameSeries(),
                            'description' => $item->game->description,
                            'release_date' => $item->game->release_date,
                            'publish_at' => $item->game->publish_at,
                            'published' => $item->game->published,
                            'website' => $item->game->website,
                            'youtube' => $item->game->youtube,
                            'youtube_btnlink' => $item->game->youtube_btnlink,
                            'twitch' => $item->game->twitch,
                            'cover' => $cover,
                            'gameGenres' => $item->game->getGameGenresList(),
                            'gamePlatformReleases' => $item->game->getGamePlatformReleasesList(),
                            'is_favorite' => $item->game->isFavorite(),
                            'is_aaa' => $item->game->is_aaa ? true : false,
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

}
