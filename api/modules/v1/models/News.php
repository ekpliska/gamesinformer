<?php

namespace api\modules\v1\models;
use common\models\News as NewsBase;

class News extends NewsBase {
    
    public function fields() {

        return [
            'id',
            'title', 
            'description', 
            'pub_date', 
            'image', 
            'link', 
            'rss' => function() {
                return $this->rss->rss_channel_name;
            },
        ];
    }

}
