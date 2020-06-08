<?php

namespace api\modules\v1\models;
use common\models\Platform as PlatformBase;

class Platform extends PlatformBase {
    
    public function fields() {
        
        return [
            'id',
            'name' => function() {
                return $this->name_platform;
            },
            'logo_path',
            'is_relevant' => function() {
                return $this->isRelevant;
            },
        ];
        
    }

}
