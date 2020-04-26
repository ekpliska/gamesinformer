<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
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
 * @property int $status
 *
 * @property UserPlatform[] $userPlatforms
 */
class User extends ActiveRecord implements IdentityInterface {
    
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public static function tableName() {
        return 'user';
    }

    public function rules()
    {
        return [
            [['password_hash', 'email', 'token'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'token' => 'Token',
            'photo' => 'Photo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    public function getUserPlatforms() {
        return $this->hasMany(UserPlatform::className(), ['user_id' => 'id']);
    }
}
