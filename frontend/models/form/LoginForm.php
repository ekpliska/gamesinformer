<?php

namespace frontend\models\form;
use Yii;
use yii\base\Model;
use frontend\models\SysUser;

/* 
 * Форма входа администратора
 */

class LoginForm extends Model {
    
    public $username;
    public $password;

    private $_user = false;
    
    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['username'], 'required', 'message' => 'Введите ваш логин'],
            [['password'], 'required', 'message' => 'Введите пароль'],
            ['password', 'validatePassword'],
        ];
    }
    
    /**
     * Проверка введенного пароля пользователем
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Данной комбинации логина и пароля не существует');
            }
        }
    }
    
    /**
     * Получить пользователя
     * Если имя пользователя найдено в БД, то разрешаем доступ к системе
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = SysUser::findByUsername($this->username);
        }

        return $this->_user;
    }
    
    public function login() {
        if ($this->validate()) {          
            return Yii::$app->user->login($this->getUser(), 0);
        }
        return false;
    }
    
    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
        ];        
    }
}

