<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "favorite".
 *
 * @property int $id
 * @property int $user_uid
 * @property int $game_id
 *
 * @property Game $game
 * @property User $userU
 */
class Favorite extends ActiveRecord {

    public static function tableName() {
        return 'favorite';
    }
    
    public function rules() {
        return [
            [['user_uid', 'game_id'], 'required'],
            [['user_uid', 'game_id'], 'integer'],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::className(), 'targetAttribute' => ['game_id' => 'id']],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'game_id' => 'Game ID',
        ];
    }

    public function getGame() {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_uid']);
    }
    
    static public function add($game_id) {
        $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => Yii::$app->user->id], ['game_id' => $game_id]])->one();
        if ($favorite) {
            return false;
        }
        $new = new Favorite();
        $new->user_uid = Yii::$app->user->id;
        $new->game_id = $game_id;
        return $new->save() ? true : false;
        
    }
    
    static public function remove($game_id) {
        
        $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => Yii::$app->user->id], ['game_id' => $game_id]])->one();
        if (!$favorite) {
            return false;
        }
        return $favorite->delete() ? true : false;
        
    }
    
}
