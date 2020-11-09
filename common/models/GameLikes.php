<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "game_likes".
 *
 * @property int $id
 * @property int $game_id
 * @property int $user_id
 *
 * @property Game $game
 * @property User $user
 */
class GameLikes extends ActiveRecord {
    
    public static function tableName() {
        return 'game_likes';
    }
    
    public function rules() {
        return [
            [['game_id', 'user_id'], 'required'],
            [['game_id', 'user_id'], 'integer'],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'user_id' => 'User ID',
        ];
    }
    
    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }
    
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
