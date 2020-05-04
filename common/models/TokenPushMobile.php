<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
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
            [['user_uid', 'token'], 'required'],
            [['user_uid'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['token'], 'unique'],
            ['enabled', 'integer'],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'id']],
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_uid']);
    }
    
    /*
     * Установка токена мобильного устройства
     * {"push_token":"key"}
     */
    public function setPushToken($_token) {
        
        // Проверяем наличие токена
        $push_token = TokenPushMobile::findOne(['token' => $_token]);
        
        // Если токена не существует то добавляем его в базу
        if (!$push_token) {
            $new_token = new TokenPushMobile();
            $new_token->user_uid = Yii::$app->user->id;
            $new_token->token = $_token;
            return $new_token->save(false) ? true : false;
        } // Если токен сущeствует, то проверяем его принадлежность текущему пользователю
        elseif ($push_token && $push_token->user_uid != Yii::$app->getUser()->id) { 
            $push_token->user_uid = Yii::$app->user->id;
            return $push_token->save(false) ? true : false;
        }
        
        return false;
        
    }
    
    /*
     * Отправка уведомления
     */
    public static function send($title = null, $message) {
        
        
        $_tokens = self::find()
                ->where(['user_uid' => $user_id])
                ->andWhere(['enabled' => true])
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
            'user_uid' => 'User Uid',
            'token' => 'Token',
            'enabled' => 'Enabled',
        ];
    }
}
