<?php

namespace api\modules\v1\models;
use common\models\Game as GameBase;

class Game extends GameBase {
    
    public function fields() {
        return parent::fields() + [
            'gameGenres' => function() {
                $geners = $this->gameGenres;
                $result = [];
                if ($geners) {
                    foreach ($geners as $gener) {
                        $result += [
                            'genre_id' => $gener->genre->id,
                            'name_genre' => $gener->genre->name_genre
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
                        $result += [
                            'patform_id' => $platfrom->platform_id,
                            'name_platform' => $platfrom->platform->name_platform,
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
