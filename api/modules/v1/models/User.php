<?php

namespace api\modules\v1\models;
use common\models\User as UserBase;

class User extends UserBase {
    
    public function fields() {
        
        return [
            'id',
            'username',
            'email',
            'photo',
            'created_at',
            'updated_at'
        ];
    }
    
}
