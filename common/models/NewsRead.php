<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "news_read".
 *
 * @property int $id
 * @property int $news_id
 * @property int $user_ids
 *
 * @property News $news
 * @property User $user
 */
class NewsRead extends ActiveRecord {

    public static function tableName() {
        return 'news_read';
    }

    public function rules() {
        return [
            [['news_id', 'user_ids'], 'required'],
            [['news_id'], 'integer'],
            [['user_ids'], 'string'],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_ids' => 'User IDs',
            'news_id' => 'News ID',
        ];
    }

    public function getNews() {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    public static function createAutoRead($news_list, $user_id) {
        if (!$user_id) {
            return false;
        }

        $user_ids = [(int)$user_id];

        foreach ($news_list as $news) {
            $news_read_model = new NewsRead();
            $news_read_model->news_id = $news->id;
            $news_read_model->user_ids = json_encode($user_ids);
            if (!$news_read_model->save()) {
                continue;
            }
        }
        return true;
    }

    public function updateAutoRead($news_read, $user_id) {
        if (!$user_id) {
            return false;
        }

        $user_ids = json_decode($news_read->user_ids);
        if (in_array($user_id, $user_ids)) {
            return false;
        }

        $user_ids[] = (int)$user_id;

        $news_read->user_ids = json_encode($user_ids);
        return $news_read->save(false);
    }

}
