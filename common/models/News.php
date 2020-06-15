<?php

namespace app\models;

use Yii;

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
 *
 * @property RssChannel $id0
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'pub_date', 'image', 'link', 'rss_channel_id'], 'required'],
            [['rss_channel_id'], 'integer'],
            [['title', 'description', 'pub_date', 'image', 'link'], 'string', 'max' => 20],
            [['rss_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => RssChannel::className(), 'targetAttribute' => ['rss_channel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'pub_date' => 'Pub Date',
            'image' => 'Image',
            'link' => 'Link',
            'rss_channel_id' => 'Rss Channel ID',
        ];
    }

    public function getId0() {
        return $this->hasOne(RssChannel::className(), ['id' => 'rss_channel_id']);
    }
}
