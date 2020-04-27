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
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'validatePassword'],
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
            $this->_user->generateToken();
            return $this->_user->save() ? $this->_user->token : null;
        } else {
            return null;
        }
    }
    
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
    
}
