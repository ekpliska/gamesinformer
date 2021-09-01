<?php

namespace api\modules\v4\models;
use common\models\Shares as SharesBase;

/**
 * Акции, скидки, распродажи, бесплатные выходные
 */
class Shares extends SharesBase {
    
    public function fields() {
        
        return [
            'id',
            'type_id' => function() {
                return $this->type_share;
            },
            'type' => function() {
                return $this->getTypeList()[$this->type_share];
            },
            'description',
            'cover',
            'link',
            'date_start',
            'date_end',
        ];
            
    }
    
}
