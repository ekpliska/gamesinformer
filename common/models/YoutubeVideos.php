<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "youtube_videos".
 *
 * @property int $id
 * @property int $youtube_rss_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $link
 * @property string|null $thumbnail
 * @property string|null $community
 * @property string|null $published
 * @property string|null $updated
 *
 * @property YoutubeRss $youtubeRss
 */
class YoutubeVideos extends ActiveRecord {

    public static function tableName() {
        return 'youtube_videos';
    }

    public function rules() {
        return [
            [['youtube_rss_id'], 'required'],
            [['youtube_rss_id'], 'integer'],
            [['description', 'community'], 'string'],
            [['published', 'updated'], 'safe'],
            [['title', 'link', 'thumbnail'], 'string', 'max' => 255],
            [['youtube_rss_id'], 'exist', 'skipOnError' => true, 'targetClass' => YoutubeRss::className(), 'targetAttribute' => ['youtube_rss_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'youtube_rss_id' => 'Youtube Rss ID',
            'title' => 'Title',
            'description' => 'Description',
            'link' => 'Link',
            'thumbnail' => 'Thumbnail',
            'community' => 'Community',
            'published' => 'Published',
            'updated' => 'Updated',
        ];
    }

    public function getYoutubeRss() {
        return $this->hasOne(YoutubeRss::className(), ['id' => 'youtube_rss_id']);
    }
}
