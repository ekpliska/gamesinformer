<?php

namespace api\modules\v3\models;
use common\models\RssChannel as RssChannelBase;

class RssChannel extends RssChannelBase {
    
    public function fields() {
        
        return [
            'id',
            'name' => function() {
                return $this->rss_channel_name;
            },
            'url' => function() {
                return $this->rss_channel_url;
            },
        ];
        
    }

}
