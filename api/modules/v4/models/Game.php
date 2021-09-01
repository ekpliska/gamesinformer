<?php

namespace api\modules\v4\models;
use yii\helpers\Url;
use common\models\Game as GameBase;

class Game extends GameBase {

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
                return $this->getGameGenresList();
            },
            'gamePlatformReleases' => function() {
                return $this->getGamePlatformReleasesList();
            },
            'is_favorite' => function() {
                return $this->isFavorite();
            },
            'comments' => function() {
                return $this->getCommentsList();
            },
            'is_aaa' => function() {
                return $this->is_aaa ? true : false;
            },
            'only_year' => function() {
                return $this->only_year ? true : false;
            },
            'tags' => function() {
                return $this->getGameTagsList();
            },
            'is_like' => function() {
                return $this->checkLike();
            },
            'count_likes' => function() {
                return (int)$this->getGameLikes()->count();
            },
        ];
    }
    
    public function getPersonalAaaGameList() {
        
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $plus_days = strtotime($date->modify('+15 day')->format('Y-m-d 00:00:00'));
        $minus_days = strtotime($date->modify('-30 day')->format('Y-m-d 00:00:00'));
        
        $games_publish = Game::find()
                ->where(['>=', 'UNIX_TIMESTAMP(publish_at)', $minus_days])
                ->andWhere(['published' => 1])
                ->andWhere(['is_aaa' => 1])
                ->limit(5)
                ->orderBy(['publish_at' => SORT_DESC]);

        $games_future = Game::find()
                ->where(['<=', 'UNIX_TIMESTAMP(publish_at)', $plus_days])
                ->andWhere(['published' => 0])
                ->andWhere(['is_aaa' => 1])
                ->limit(10 - count($games_publish->count()))
                ->orderBy(['publish_at' => SORT_DESC]);
        
        
        return array_merge(
            $games_publish->orderBy(['publish_at' => SORT_ASC])->all(), 
            $games_future->orderBy(['publish_at' => SORT_ASC])->all()
        );
        
    }
    
}
