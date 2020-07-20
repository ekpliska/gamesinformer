<?php

namespace api\modules\v3\models;
use common\models\Genre as GenreBase;

class Genre extends GenreBase {
    
    public function fields() {
        
        return [
            'id',
            'name' => function() {
                return $this->name_genre;
            },
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
                return [];
            },
        ];
        
    }

}
