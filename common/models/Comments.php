<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property int $game_id
 * @property string $message
 * @property int $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Game $game
 * @property User $user
 */
class Comments extends ActiveRecord {

    public static function tableName() {
        return 'comments';
    }

    public function rules() {
        return [
            [['game_id', 'message', 'user_id'], 'required'],
            [['game_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message'], 'string', 'max' => 256],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'game_id' => 'Игра',
            'message' => 'Сообщение',
            'user_id' => 'Пользователь',
            'created_at' => 'Дата отправки',
            'updated_at' => 'Дата редактирования',
        ];
    }

    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
