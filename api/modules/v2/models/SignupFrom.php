<?php

namespace api\modules\v2\models;
use Yii;
use yii\base\Model;
use common\models\User;

/* 
 * Регистрация пользователя
 */

class SignupFrom extends Model {
    
    public $email;
    public $password;
    public $device_token;


    public function rules() {
        return [
            [['email'], 'required', 'message' => 'Не указан email'],
            [['password'], 'required', 'message' => 'Не указан пароль'],
            [['email', 'password'], 'trim'],
            ['email','email', 'message' => 'Email некорректный'],
            [
                'password', 
                'string', 
                'length' => [6, 8]
            ],
            ['device_token', 'string'],
            ['email', 'checkUniqueEmail'],
        ];
    }
    
    public function checkUniqueEmail($attribute, $param) {
        
        if (!$this->hasErrors()) {
            $user = User::findOne(['email' => $this->$attribute]);
            if ($user) {
                $this->addError($attribute, 'Указанный email уже используется');
            }
        }
    }
    
    public function register() {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->username = substr($this->email, 0, strrpos($this->email, '@'));
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->generateToken();
            if ($this->device_token != null) {
                $device_token = PushNotification::findOne(['token' => $this->device_token]);
                if ($device_token) {
                    $device_token->is_auth = true;
                    $device_token->save(false);
                }
            }
            return $user->save() ? $user : false;
        }
        return false;
    }

}

