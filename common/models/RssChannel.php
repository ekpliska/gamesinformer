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
            ['rss_channel_url', 'url', 'message' => 'Вы указали некорректный  url адрес'],
        ];
    }
    
    public function afterDelete() {
        parent::afterDelete();
        News::deleteAll(['rss_channel_id' => $this->id]);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'rss_channel_name' => 'Название ленты',
            'rss_channel_url' => 'Ссылка на ленту',
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
