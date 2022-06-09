<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\FavoriteSeries;

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
 * @property string|null $logout_at
 * @property string|null $time_alert
 * @property int $is_time_alert
 * @property int $is_advertising // Не актуальная настройка
 * @property int $is_shares
 * @property string|null $days_of_week
 * @property int $status
 * @property int $aaa_notifications
 * @property int $is_subscription
 * @property int $is_favorite_list
 * @property int $is_favorite_series
 * 
 *
 * @property UserPlatform[] $userPlatforms
 */
class User extends ActiveRecord implements IdentityInterface {
    
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 1;
    
    const DAYS_OF_WEEK = ['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'];

    public static function tableName() {
        return 'user';
    }

    public function rules()
    {
        return [
            [['password_hash', 'email', 'token'], 'required'],
            [['created_at', 'updated_at', 'time_alert', 'logout_at'], 'safe'],
            [['status', 'aaa_notifications', 'is_time_alert', 'is_advertising', 'is_shares'], 'integer'],
            [['username', 'password_hash', 'photo'], 'string', 'max' => 255],
            [['auth_key', 'token'], 'string', 'max' => 32],
            [['days_of_week'], 'string', 'max' => 70],
            [['email'], 'string', 'max' => 70],
            [['email'], 'unique'],
            [['token'], 'unique'],

            [['is_time_alert', 'is_subscription'], 'default', 'value' => 0],
            [['aaa_notifications', 'is_shares', 'is_advertising', 'is_favorite_list', 'is_favorite_series'], 'default', 'value' => 1],
            
            // [['aaa_notifications', 'is_shares'], 'default', 'value' => 0],
            // [['is_time_alert', 'is_advertising', 'is_subscription'], 'default', 'value' => 0],
            // [['is_favorite_list', 'is_favorite_series'], 'default', 'value' => 1],
        ];
    }
    
    public function beforeSave($insert) {
        if ($this->is_time_alert == 0) {
            $this->time_alert = null;
            $this->days_of_week = null;
        }
        return parent::beforeSave($insert);
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
        if ($this->token) {
            return $this->token;
        }
        return $this->token = \Yii::$app->security->generateRandomString();
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
            'logout_at' => 'Дата выхода из приложения',
            'status' => 'Статус',
            'time_alert' => 'Время оповещений',
            'aaa_notifications' => 'Уведомления о выходе AAA-игр',
            'is_time_alert' => 'Оповещения по дням недели',
            'days_of_week' => 'Дни недели',
            'is_subscription' => 'Подписка',
            'is_favorite_list' => 'Уведомления о выходе игры из избранного',
            'is_favorite_series' => 'Уведомления об изменении в избранной серии',
        ];
    }

    public function getUserPlatforms() {
        return $this->hasMany(UserPlatform::className(), ['user_id' => 'id']);
    }
    
    public function getUserFavorite() {
        return $this->hasMany(Favorite::className(), ['user_uid' => 'id']);
    }
    
    public function getUserFavoriteSeries() {
        return $this->hasMany(FavoriteSeries::className(), ['user_uid' => 'id']);
    }

    public function subscribe() {
        $this->is_subscription = true;
        $this->aaa_notifications = 1;
        $this->is_shares = 1;
        return $this->save() ? true : false;
    }
    
    public function unSubscribe() {
        $this->is_subscription = false;
        $this->aaa_notifications = 0;
        $this->is_shares = 0;
        return $this->save() ? true : false;
    }

    public function setLogoutDate() {
        $this->logout_at = \Yii::$app->formatter->asDate(new \DateTime('NOW'), 'yyyy-MM-dd hh:mm:ss');;
        return $this->save() ? true : false;
    }
    
}
