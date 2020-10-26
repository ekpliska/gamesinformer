<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use common\components\notifications\Notifications;

/**
 * This is the model class for table "game_series".
 *
 * @property int $id
 * @property int $series_id
 * @property int $game_id
 *
 * @property Game $game
 * @property Series $series
 */
class GameSeries extends ActiveRecord {

    public static function tableName() {
        return 'game_series';
    }

    public function rules() {
        return [
            [['series_id', 'game_id'], 'required'],
            [['series_id', 'game_id'], 'integer'],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
            [['series_id'], 'exist', 'skipOnError' => true, 'targetClass' => Series::className(), 'targetAttribute' => ['series_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'series_id' => 'Series ID',
            'game_id' => 'Game ID',
        ];
    }

    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getSeries() {
        return $this->hasOne(Series::className(), ['id' => 'series_id']);
    }
}
