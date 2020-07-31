<?php

namespace common\models;
use yii\db\ActiveRecord;
use Yii;
use common\models\NewsViews;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $pub_date
 * @property string $image
 * @property string $link
 * @property string $updated
 * * @property string $community
 * @property int $rss_channel_id
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
            [['description', 'community'], 'string'],
            [['pub_date', 'updated'], 'safe'],
            [['rss_channel_id'], 'integer'],
            [['title', 'image', 'link'], 'string', 'max' => 255],
            [['rss_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => RssChannel::className(), 'targetAttribute' => ['rss_channel_id' => 'id']],
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->pub_date = \Yii::$app->formatter->asDate($this->pub_date, 'yyyy-MM-dd hh:mm:ss');
            $this->updated = \Yii::$app->formatter->asDate($this->updated, 'yyyy-MM-dd hh:mm:ss');
            $this->description = strip_tags($this->description);
            return true;
        }
        return false;
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'pub_date' => 'Pub Date',
            'updated' => 'Updated',
            'community' => 'Community',
            'image' => 'Image',
            'link' => 'Link',
            'rss_channel_id' => 'Rss Channel ID',
        ];
    }

    public function getRss() {
        return $this->hasOne(RssChannel::className(), ['id' => 'rss_channel_id']);
    }
    
    public function getViews() {
        return $this->hasMany(NewsViews::className(), ['news_id' => 'id']);
    }
    
    public static function checkNews($title) {
        $news = self::find()
                ->where(['title' => $title])
                ->one();
        return $news ? true : false;
    }
}
