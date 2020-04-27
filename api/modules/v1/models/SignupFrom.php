<?php

namespace api\modules\v1\models;
use Yii;
use yii\base\Model;
use common\models\User;

/* 
 * Регистрация пользователя
 */

class SignupFrom extends Model {
    
    public $email;
    public $password;
    
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
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->generateToken();
            return $user->save() ? ['success' => true, 'token' => $user->token] : ['success' => false, 'error' => 'Ошибка регистрации. Повторите позже!'];
        }
        
        return ['success' => false, 'error' => 'Ошибка регистрации. Повторите позже!'];
        
    }

}

