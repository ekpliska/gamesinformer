<?php

namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Пользователи приложения, Администраторы
 */
class SysUser extends ActiveRecord implements IdentityInterface {
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function rules() {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public static function tableName() {
        return 'sys_user';
    }
    
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null) {
        //
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getAuthKey() {
        return $this->auth_key;
    }
    
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }
    
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }
    
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
