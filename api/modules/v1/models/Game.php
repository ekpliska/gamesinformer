<?php

namespace api\modules\v1\models;
use yii\helpers\Url;
use common\models\Game as GameBase;

class Game extends GameBase {
    
    public function fields() {
        
        return [
            'id', 'title', 'description', 'series', 
            'release_date', 
            'publish_at', 'published', 
            'website', 'youtube', 'youtube_btnlink', 'twitch',
            'cover' => function() {
                if (strpos($this->cover, 'youtube.com')) {
                    return $this->cover;
                }
                return Url::home(true) . ltrim($this->cover, '/');
            },
            'gameGenres' => function() {
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
            },
            'gamePlatformReleases' => function() {
                $platfroms = $this->gamePlatformReleases;
                $result = [];
                if ($platfroms) {
                    foreach ($platfroms as $platfrom) {
                        $result[] = [
                            'id' => $platfrom->platform_id,
                            'name' => $platfrom->platform->name_platform,
                            'date_platform_release' => $platfrom->date_platform_release,
                            'logo_path' => $platfrom->platform->logo_path,
                        ];
                    }
                }
                return $result;
            },
        ];
    }
    
}
