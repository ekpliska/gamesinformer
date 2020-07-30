<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "youtube_videos_views".
 *
 * @property int $id
 * @property int $user_id
 * @property int $youtube_videos_id
 *
 * @property User $user
 * @property YoutubeVideos $youtubeVideos
 */
class YoutubeVideosViews extends ActiveRecord {

    public static function tableName() {
        return 'youtube_videos_views';
    }

    public function rules() {
        return [
            [['user_id', 'youtube_videos_id'], 'required'],
            [['user_id', 'youtube_videos_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['youtube_videos_id'], 'exist', 'skipOnError' => true, 'targetClass' => YoutubeVideos::className(), 'targetAttribute' => ['youtube_videos_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'youtube_videos_id' => 'Youtube Videos ID',
        ];
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getYoutubeVideos() {
        return $this->hasOne(YoutubeVideos::className(), ['id' => 'youtube_videos_id']);
    }
}
