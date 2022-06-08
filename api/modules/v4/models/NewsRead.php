<?php

namespace api\modules\v4\models;
use common\models\NewsRead as NewsReadBase;

class NewsRead extends NewsReadBase {

    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
    }

    public function getReadNewsIdsByUserId() {
        $news_ids_list = [];

        if (!$this->_user) {
            return $news_ids_list;
        }


        $news_read_list = NewsReadBase::find()->all();
        if (count($news_read_list)) {
            foreach ($news_read_list as $item) {
                $user_ids = json_decode($item->user_ids);
                if (in_array($this->_user->id, $user_ids)) {
                    $news_ids_list[] = $item->news_id;
                }
            }
        }

        return $news_ids_list;
    }

    private function checkAuthUser() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->one();
            return $user;
        }
        return false;
    }
}
