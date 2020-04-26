<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_platform".
 *
 * @property int $id
 * @property int $user_id
 * @property int $platform_id
 *
 * @property Platform $platform
 * @property User $user
 */
class UserPlatform extends ActiveRecord {

    public static function tableName() {
        return 'user_platform';
    }

    public function rules() {
        return [
            [['user_id', 'platform_id'], 'required'],
            [['user_id', 'platform_id'], 'integer'],
            [['platform_id'], 'exist', 'skipOnError' => true, 'targetClass' => Platform::className(), 'targetAttribute' => ['platform_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'platform_id' => 'Platform ID',
        ];
    }

    public function getPlatform() {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
