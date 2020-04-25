<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Релизы на платформе
 *
 * @property int $id
 * @property int $game_id
 * @property int $platform_id
 * @property string $date_platform_release
 *
 * @property Game $game
 * @property Platform $platform
 */
class GamePlatformRelease extends ActiveRecord {
    
    public static function tableName() {
        return 'game_platform_release';
    }

    public function rules() {
        return [
            [['game_id', 'platform_id', 'date_platform_release'], 'required'],
            [['game_id', 'platform_id'], 'integer'],
            [['date_platform_release'], 'safe'],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
            [['platform_id'], 'exist', 'skipOnError' => true, 'targetClass' => Platform::className(), 'targetAttribute' => ['platform_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'platform_id' => 'Platform ID',
            'date_platform_release' => 'Date Platform Release',
        ];
    }

    /**
     * Связь с играми
     */
    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    /**
     * Связь с платформами
     */
    public function getPlatform() {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }
}
