<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "top_games".
 *
 * @property int $id
 * @property string $type_characteristic
 * @property int $type_characteristic_id
 * @property int $game_id
 *
 * @property Game $game
 * @property Genre $typeCharacteristic
 * @property Platform $typeCharacteristic0
 */
class TopGames extends ActiveRecord {
    
    const TYPE_CHARACTERISTIC_PALFORM = 'platform';
    const TYPE_CHARACTERISTIC_GENRE = 'genre';

    public static function tableName() {
        return 'top_games';
    }

    public function rules() {
        return [
            [['type_characteristic', 'type_characteristic_id', 'game_id'], 'required'],
            [['type_characteristic_id', 'game_id'], 'integer'],
            [['type_characteristic'], 'string', 'max' => 10],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type_characteristic' => 'Type Characteristic',
            'type_characteristic_id' => 'Type Characteristic ID',
            'game_id' => 'Game ID',
        ];
    }

    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getTypeCharacteristicByGenre() {
        return $this->hasOne(Genre::className(), ['id' => 'type_characteristic_id']);
    }

    public function getTypeCharacteristicByPlatform() {
        return $this->hasOne(Platform::className(), ['id' => 'type_characteristic_id']);
    }
}