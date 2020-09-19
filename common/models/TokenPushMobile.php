<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\components\firebasePush\FirebaseNotifications;

/**
 * Пуш уведомления
 */
class TokenPushMobile extends ActiveRecord {

    public static function tableName() {
        return 'token_push_mobile';
    }

    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            ['enabled', 'integer'],
            [['is_auth', 'user_uid'], 'integer'],
        ];
    }
    
    /*
     * Установка токена мобильного устройства
     * {"push_token":"key"}
     */
    public function setPushToken($_token, $user_id = null) {
        
        // Проверяем наличие токена
        $push_token = TokenPushMobile::findOne(['token' => $_token]);
        
        // Если токена не существует то добавляем его в базу
        if (!$push_token) {
            $new_token = new TokenPushMobile();
            $new_token->token = $_token;
            $new_token->is_auth = $user_id ? 1 : 0;
            $new_token->user_uid = $user_id;
            return $new_token->save(false) ? true : false;
        } elseif ($push_token && $user_id) {
            $push_token->is_auth = 1;
            return $push_token->save(false) ? true : false;
        }
        
        return false;
        
    }
    
    /*
     * Отправка уведомления
     */
    public static function send($title = null, $message) {
        
        
        $_tokens = self::find()
                ->andWhere(['enabled' => true])
                ->andWhere(['is_auth' => true])
                ->asArray()
                ->all();
        
        // Если массив токенов не пустой, то отправляем push-уведомления
        if (!empty($_tokens)) {
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
            $message = [
                'title' => "{$title}",
                'body' => $message
            ];

            $notes = new FirebaseNotifications();
            return $notes->sendNotification($tokens, $message);            
        }
        
        return true;
    }
        
    /**
     * Атрибуты полей
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'enabled' => 'Enabled',
        ];
    }
}
