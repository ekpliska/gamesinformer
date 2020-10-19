<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use common\models\Game;
use common\models\News;

/**
 * This is the model class for table "tag_link".
 *
 * @property int $id
 * @property string $type
 * @property int $type_uid
 * @property int $tag_id
 */
class TagLink extends ActiveRecord {

    const TYPE_LIST = [
        501 => 'news',
        502 => 'games',
    ];
    
    public static function tableName() {
        return 'tag_link';
    }

    public function rules() {
        return [
            [['type', 'type_uid'], 'required'],
            [['type_uid'], 'integer'],
            [['type'], 'string', 'max' => 10],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }
    
    static public function getTypeList() {
        return self::TYPE_LIST;
    }

    public function getTag() {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
    
    public function getGameList() {
        return $this->hasMany(Game::className(), ['id' => 'type_uid'], ['type' => self::TYPE_LIST[502]]);
    }
    
    public function getNewsList() {
        return $this->hasMany(News::className(), ['id' => 'type_uid'], ['type' => self::TYPE_LIST[501]]);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'type_uid' => 'Type Uid',
            'tag_id' => 'Tag ID',
        ];
    }
}
