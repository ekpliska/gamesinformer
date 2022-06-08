<?php

namespace common\models;
use yii\db\ActiveRecord;
use Yii;
use common\models\NewsViews;
use common\models\NewsRead;
use common\models\TagLink;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $pub_date
 * @property string $image
 * @property string $link
 * @property int $rss_channel_id
 * @property int $is_block
 * @property int $number_views
 *
 * @property RssChannel $id0
 */
class News extends ActiveRecord {

    public static function tableName() {
        return 'news';
    }

    public function rules() {
        return [
            [['title', 'description', 'pub_date', 'link', 'rss_channel_id'], 'required'],
            [['description'], 'string'],
            [['pub_date'], 'safe'],
            [['rss_channel_id', 'is_block', 'number_views'], 'integer'],
            [['title', 'image', 'link'], 'string', 'max' => 255],
            [['rss_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => RssChannel::className(), 'targetAttribute' => ['rss_channel_id' => 'id']],
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->pub_date = \Yii::$app->formatter->asDate($this->pub_date, 'yyyy-MM-dd hh:mm:ss');
            $this->description = strip_tags(html_entity_decode($this->description));
            return true;
        }
        return false;
    }
    
    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $tags = Tag::find()->all();
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    if (mb_stripos("NEWS_{$this->title}", $tag->name)) {
                        $tag_link = new TagLink();
                        $tag_link->type = TagLink::TYPE_LIST[501];
                        $tag_link->type_uid = $this->id;
                        $tag_link->tag_id = $tag->id;
                        $tag_link->save(false);
                    }
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

        public function afterDelete() {
        parent::afterDelete();
        TagLink::deleteAll(['AND', ['type_uid' => $this->id], ['type' => TagLink::TYPE_LIST[501]]]);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'pub_date' => 'Pub Date',
            'image' => 'Image',
            'link' => 'Link',
            'rss_channel_id' => 'Rss Channel ID',
            'is_block' => 'Is Block',
            'number_views' => 'Number Views',
        ];
    }

    public function getRss() {
        return $this->hasOne(RssChannel::className(), ['id' => 'rss_channel_id']);
    }
    
    public function getViews() {
        return $this->hasMany(NewsViews::className(), ['news_id' => 'id']);
    }

    public function getReads() {
        return $this->hasOne(NewsRead::className(), ['news_id' => 'id']);
    }
    
    public function getLikes() {
        return $this->hasMany(NewsLikes::className(), ['news_id' => 'id']);
    }
    
    public function getTagsList() {
        return $this->hasMany(TagLink::className(), ['type_uid' => 'id'], ['type' => TagLink::TYPE_LIST[501]]);
    }
    
    public function getNewseTagsList() {
        $tags = $this->tagsList;
        $result = [];
        if ($tags) {
            foreach ($tags as $item) {
                $result[] = $item->tag->name;
            }
        }
        return $result;
    }

    public function checkNewsReadByUserId($user_id) {
        $reads = $this->reads;
        if (!$this->reads) {
            return false;
        }
        
        $user_ids = json_decode($reads->user_ids);
        return in_array($user_id, $user_ids);
    }
    
    public static function checkNews($title) {
        $news = self::find()
                ->where(['title' => $title])
                ->one();
        return $news ? true : false;
    }

    public static function findByLink($link) {
        $news = self::find()
            ->where(['link' => $link])
            ->one();
        return $news ? true : false;
    }

}
