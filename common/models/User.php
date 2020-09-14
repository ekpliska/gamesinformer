<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $token
 * @property string|null $photo
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $time_alert
 * @property int $status
 * @property int $aaa_notifications
 * 
 *
 * @property UserPlatform[] $userPlatforms
 */
class User extends ActiveRecord implements IdentityInterface {
    
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 1;

    public static function tableName() {
        return 'user';
    }

    public function rules()
    {
        return [
            [['password_hash', 'email', 'token'], 'required'],
            [['created_at', 'updated_at', 'time_alert'], 'safe'],
            [['status', 'aaa_notifications'], 'integer'],
            [['username', 'password_hash', 'photo'], 'string', 'max' => 255],
            [['auth_key', 'token'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 70],
            [['email'], 'unique'],
            [['token'], 'unique'],
        ];
    }
    
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['token' => $token]);
    }
    
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    public static function findByEmail($email) {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId() {
        return $this->getPrimaryKey();
    }
    
    public function getAuthKey() {
        return $this->auth_key;
    }
    
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }
    
    public function generateToken() {
        $this->token = \Yii::$app->security->generateRandomString();
    }
    
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Хешпароль',
            'email' => 'Email',
            'token' => 'Token',
            'photo' => 'Фото профиля',
            'created_at' => 'Зарегистрирован',
            'updated_at' => 'Профиль обновлен',
            'status' => 'Статус',
            'time_alert' => 'Время оповещений',
            'aaa_notifications' => 'Уведомления о выходе AAA-игр',
        ];
    }

    public function getUserPlatforms() {
        return $this->hasMany(UserPlatform::className(), ['user_id' => 'id']);
    }
    
    public function getUserFavorite() {
        return $this->hasMany(Favorite::className(), ['user_uid' => 'id']);
    }
    
}
