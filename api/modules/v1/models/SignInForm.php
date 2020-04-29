<?php

namespace api\modules\v1\models;
use yii\base\Model;
use common\models\User;

/**
 * Вход, авторизация
 */
class SignInForm extends Model {
    
    public $email;
    public $password;
    
    private $_user;
    
    public function rules() {
        return [
            [['email'], 'required', 'message' => 'Не указан email'],
            [['password'], 'required', 'message' => 'Не указан пароль'],
            ['email', 'email', 'message' => 'Email некорректный'],
            ['password', 'validatePassword'],
            ['password', 'string', 'length' => [6, 8]],
        ];
    }
    
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
    
    public function auth() {
        if ($this->validate()) {
            $user = $this->_user;
            $user->generateToken();
            return $user->save() ? ['success' => true, 'token' => $user->token] : ['success' => false, 'error' => 'Произошла ошибка авторизации. Повторите позже!'];
        }
        
        return false;
    }
    
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
    
}
