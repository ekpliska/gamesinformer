<?php

namespace api\modules\v4\models\forms;
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
            $user->login_at = \Yii::$app->formatter->asDate(new \DateTime('NOW'), 'yyyy-MM-dd hh:mm:ss');
            return $user->save() ? $user->token : false;
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
