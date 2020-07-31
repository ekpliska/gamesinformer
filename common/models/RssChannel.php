<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use common\models\News;

/**
 * This is the model class for table "rss_channel".
 *
 * @property int $id
 * @property string $rss_channel_name
 * @property string $type
 * @property string $channel_id
 * @property string $rss_channel_url
 * @property string $site_url
 * @property string $title_tag
 * @property string $description_tag
 * @property string $pub_date_tag
 * @property string $image_tag
 * @property string $link_tag
 *
 * @property News $news
 */
class RssChannel extends ActiveRecord {
    
    const TYPE_NEWS = '100';
    const TYPE_YOUTUBE = '101';
    
    const SCENARIO_FOR_NEWS_RSS = 'for_news_rss';
    const SCENARIO_FOR_YOUTUBE_RSS = 'for_youtube_rss';

    public static function tableName() {
        return 'rss_channel';
    }

    public function rules() {
        return [
            [[
                'rss_channel_name', 'rss_channel_url', 'type',
                'title_tag', 'description_tag', 'pub_date_tag', 'link_tag',
                ], 'required', 'on' => self::SCENARIO_FOR_NEWS_RSS,
            ],
            [['rss_channel_name', 'channel_id', 'type', 'site_url'],
                'required', 'on' => self::SCENARIO_FOR_YOUTUBE_RSS,
            ],
            [['rss_channel_name', 'type'], 'required'],
            [['type'], 'string', 'max' => 10],
            [['rss_channel_name'], 'string', 'max' => 70],
            [['rss_channel_url', 'site_url'], 'string', 'max' => 255],
            [['title_tag', 'description_tag', 'pub_date_tag', 'image_tag', 'link_tag'], 'string', 'max' => 20],
            [['rss_channel_url', 'site_url'], 'url', 'message' => 'Вы указали некорректный  url адрес'],
        ];
    }

    public function beforeSave($insert) {
        if ($this->type === self::TYPE_YOUTUBE) {
            $this->rss_channel_url = "https://www.youtube.com/feeds/videos.xml?channel_id={$this->channel_id}";
        }
        return parent::beforeSave($insert);
    }

    public function afterDelete() {
        parent::afterDelete();
        News::deleteAll(['rss_channel_id' => $this->id]);
    }
    
    public static function getTypesList() {
        return [
            self::TYPE_NEWS => 'RSS новости',
            self::TYPE_YOUTUBE => 'YouTube лента',
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Раздел',
            'channel_id' => 'Channel ID',
            'rss_channel_name' => 'Название ленты',
            'rss_channel_url' => 'Ссылка на ленту',
            'site_url' => 'Источник (канал, сайт)',
            'title_tag' => 'Тег заголовка новости',
            'description_tag' => 'Тег описание новости',
            'pub_date_tag' => 'Тег даты публикации новости',
            'image_tag' => 'Тег превью новости',
            'link_tag' => 'Тег источник новости',
        ];
    }

    public function getNews() {
        return $this->hasOne(News::className(), ['rss_channel_id' => 'id']);
    }
    
    public function getRssTags() {
        return [
            'title' => $this->title_tag,
            'description' => $this->description_tag,
            'pub_date' => $this->pub_date_tag,
            'image' => $this->image_tag,
            'link' => $this->link_tag,
        ];
    }

}
