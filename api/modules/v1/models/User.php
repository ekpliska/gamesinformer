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
            'updated_at',
            'platforms' => function() {
                $platforms = $this->userPlatforms;
                $result = [];
                if ($platforms) {
                    foreach ($platforms as $platform) {
                        $result[] = [
                            'id' => $platform->platform_id,
                            'name' => $platform->platform->name_platform,
                            'logo_path' => $platform->platform->logo_path
                        ];
                    }
                }
                return $result;
            }
        ];
    }
    
}
