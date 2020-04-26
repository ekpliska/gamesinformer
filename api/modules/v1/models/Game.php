<?php

namespace api\modules\v1\models;
use common\models\Game as GameBase;

class Game extends GameBase {
    
    public function fields() {
        return parent::fields() + [
            'gameGenres' => function() {
                return $this->gameGenres;
            },
            'gamePlatformReleases' => function() {
                return $this->gamePlatformReleases;
            },
        ];
    }
    
}
