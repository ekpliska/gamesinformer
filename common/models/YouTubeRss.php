<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "youtube_rss".
 *
 * @property int $id
 * @property string $channel_id
 * @property string|null $name
 * @property string|null $url
 *
 * @property YoutubeVideos[] $youtubeVideos
 */
class YouTubeRss extends ActiveRecord {

    public static function tableName() {
        return 'youtube_rss';
    }

    public function rules() {
        return [
            [['channel_id'], 'required'],
            [['channel_id', 'name'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'channel_id' => 'ID канала',
            'name' => 'Название',
            'url' => 'Ссылка на канал',
        ];
    }

    public function getYoutubeVideos() {
        return $this->hasMany(YoutubeVideos::className(), ['youtube_rss_id' => 'id']);
    }
}
