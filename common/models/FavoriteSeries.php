<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use common\models\Series;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "favorite_series".
 *
 * @property int $id
 * @property int $user_uid
 * @property int $game_id
 *
 * @property Game $game
 * @property User $userU
 */
class FavoriteSeries extends ActiveRecord {

    public static function tableName() {
        return 'favorite_series';
    }
    
    public function rules() {
        return [
            [['user_uid', 'series_id'], 'required'],
            [['user_uid', 'series_id'], 'integer'],
            [['series_id'], 'exist', 'skipOnError' => true, 'targetClass' => Series::className(), 'targetAttribute' => ['series_id' => 'id']],
            [['user_uid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_uid' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_uid' => 'User Uid',
            'series_id' => 'Series ID',
        ];
    }

    public function getSeries() {
        return $this->hasOne(series_id::className(), ['id' => 'series_id']);
    }

    public function getGameSeries() {
        return $this->hasMany(GameSeries::className(), ['series_id' => 'series_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_uid']);
    }

    static public function add($series_id) {
        $favorite = FavoriteSeries::find()->andWhere(['AND', ['user_uid' => Yii::$app->user->id], ['series_id' => $series_id]])->one();
        if ($favorite) {
            return false;
        }
        $new = new FavoriteSeries();
        $new->user_uid = Yii::$app->user->id;
        $new->series_id = $series_id;
        return $new->save() ? true : false;
        
    }
    
    static public function remove($series_id) {
        
        $favorite = FavoriteSeries::find()->andWhere(['AND', ['user_uid' => Yii::$app->user->id], ['series_id' => $series_id]])->one();
        if (!$favorite) {
            return false;
        }
        return $favorite->delete() ? true : false;
        
    }

    static public function getGamesByUserId($user_id) {
        if (!$user_id) {
            return [];
        }

        $favorite = FavoriteSeries::find()
            ->andWhere(['user_uid' => $user_id])
            ->joinWith('gameSeries')
            ->asArray()
            ->all();

        $ids = [];
        if ($favorite && count($favorite)) {
            foreach ($favorite as $item) {
                $ids = ArrayHelper::merge($ids, ArrayHelper::getColumn($item['gameSeries'], function ($i) {
                    return (int)$i['game_id'];
                }));
            }
        }

        return $ids;
    }
    
}
