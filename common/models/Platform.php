<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Платформы
 *
 * @property int $id
 * @property string $name_platform
 *
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Platform extends ActiveRecord {

    public static function tableName() {
        return 'platform';
    }

    public function rules() {
        return [
            [['name_platform'], 'required'],
            [['name_platform'], 'string', 'max' => 255],
            [['name_platform'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name_platform' => 'Name Platform',
        ];
    }

    public function getGamePlatformReleases() {
        return $this->hasMany(GamePlatformRelease::className(), ['platform_id' => 'id']);
    }
}
