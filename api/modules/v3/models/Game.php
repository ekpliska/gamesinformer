<?php

namespace api\modules\v3\models;
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
        ];
    }
    
    public function getPersonalAaaGameList() {
        
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $plus_days = strtotime($date->modify('+15 day')->format('Y-m-d 00:00:00'));
        $minus_days = strtotime($date->modify('-30 day')->format('Y-m-d 00:00:00'));
        
        $games_future = Game::find()
                ->where(['<=', 'UNIX_TIMESTAMP(release_date)', $plus_days])
                ->andWhere(['published' => 0])
                ->andWhere(['is_aaa' => 1])
                ->limit(5)
                ->orderBy(['release_date' => SORT_ASC]);
        
        $games_publish = Game::find()
                ->where(['>=', 'UNIX_TIMESTAMP(release_date)', $minus_days])
                ->andWhere(['published' => 1])
                ->andWhere(['is_aaa' => 1])
                ->limit(5)
                ->orderBy(['release_date' => SORT_DESC]);        
        
        return array_merge($games_future->all(), $games_publish->all());
        
    }
    
}
