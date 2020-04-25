<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Жанры
 *
 * @property int $id
 * @property int $game_id
 *
 * @property Game $game
 */
class GameGenre extends ActiveRecord {

    public static function tableName() {
        return 'game_genre';
    }

    public function rules() {
        return [
            [['game_id'], 'required'],
            [['game_id'], 'integer'],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
        ];
    }

    /**
     * Связь с играми
     */
    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
}
