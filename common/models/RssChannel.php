<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rss_channel".
 *
 * @property int $id
 * @property string $rss_channel_name
 * @property string $rss_channel_url
 * @property string $title_tag
 * @property string $description_tag
 * @property string $pub_date_tag
 * @property string $image_tag
 * @property string $link_tag
 *
 * @property News $news
 */
class RssChannel extends ActiveRecord {

    public static function tableName() {
        return 'rss_channel';
    }

    public function rules() {
        return [
            [['rss_channel_name', 'rss_channel_url', 'title_tag', 'description_tag', 'pub_date_tag', 'image_tag', 'link_tag'], 'required'],
            [['rss_channel_name'], 'string', 'max' => 70],
            [['rss_channel_url'], 'string', 'max' => 255],
            [['title_tag', 'description_tag', 'pub_date_tag', 'image_tag', 'link_tag'], 'string', 'max' => 20],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'rss_channel_name' => 'Rss Channel Name',
            'rss_channel_url' => 'Rss Channel Url',
            'title_tag' => 'Title Tag',
            'description_tag' => 'Description Tag',
            'pub_date_tag' => 'Pub Date Tag',
            'image_tag' => 'Image Tag',
            'link_tag' => 'Link Tag',
        ];
    }

    public function getNews() {
        return $this->hasOne(News::className(), ['rss_channel_id' => 'id']);
    }
}
