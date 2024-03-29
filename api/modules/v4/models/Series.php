<?php

namespace api\modules\v4\models;
use common\models\Series as SeriesBase;
use yii\helpers\Url;

class Series extends SeriesBase {
    
    public function fields() {
        
        return parent::fields() + [
            'is_favorite' => function() {
                return $this->isFavorite();
            },
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
                            'comments' => $item->game->getCommentsList(),
                            'is_aaa' => $item->game->is_aaa ? true : false,
                            'only_year' => $item->game->only_year ? true : false,
                            'tags' => $item->game->getGametagsList(),
                            'is_like' => $item->game->checkLike(),
                            'count_likes' => $item->game->getGameLikes()->count(),
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
