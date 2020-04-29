<?php

namespace api\modules\v1\models;
use common\models\Genre as GenreBase;

class Genre extends GenreBase {
    
    public function fields() {
        
        return [
            'id',
            'name' => function() {
                return $this->name_genre;
            }
        ];
        
    }

}
