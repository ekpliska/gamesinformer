<?php

namespace api\modules\v1\models;
use Yii;
use yii\base\Model;
use api\modules\v1\models\User;

/**
 * Изменить пароль учетной записи
 */
class ChangePassword extends Model {
    
    public $old_password;
    public $new_password;
    public $repeat_password;
    
    public $_user;
    
    public function __construct(User $user, $config = []) {
        $this->_user = $user;
        parent::__construct($config);
    }
    
    public function rules() {
        
        return [
            [['old_password', 'new_password', 'repeat_password'], 'required'],
            [['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 6, 'max' => 8],
            ['new_password', 'compare', 'compareAttribute' => 'repeat_password', 'message' => 'Пароль и подтверждение пароля не совпадают'],
            ['old_password', 'checkOldPassword'],
        ];
        
    }
    
    public function checkOldPassword($attribute, $param) {
        
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, 'Старый пароль указан неверно');
            }
        }
    }
    
    public function changePassword() {
        
        if ($this->validate()) {
            $user = $this->_user;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->new_password);
            $user->generateToken();
            
            if (!$user->save()) {
                return ['success' => false, 'error' => 'Произошла ошибка смены пароля. Повторите позже!'];
            }
            return ['success' => true, 'token' => $user->token];
        }
        
        return false;
        
    }
    
}
