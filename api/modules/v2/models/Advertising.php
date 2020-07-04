<?php

namespace api\modules\v2\models;
use yii\helpers\Url;
use common\models\Advertising as AdvertisingBase;

class Advertising extends AdvertisingBase {
    
    public function fields() {
        
        return [
            'id',
            'title',
            'description',
            'preview' => function() {
                if (strpos($this->preview, 'youtube.com')) {
                    return $this->preview;
                }
                return Url::home(true) . ltrim($this->preview, '/');
            },
            'youtube',
        ];
        
    }
}
