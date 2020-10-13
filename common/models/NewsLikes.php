<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news_likes".
 *
 * @property int $id
 * @property int $user_id
 * @property int $news_id
 *
 * @property News $news
 * @property User $user
 */
class NewsLikes extends ActiveRecord {

    public static function tableName() {
        return 'news_likes';
    }

    public function rules() {
        return [
            [['user_id', 'news_id'], 'required'],
            [['user_id', 'news_id'], 'integer'],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'news_id' => 'News ID',
        ];
    }

    public function getNews() {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
}