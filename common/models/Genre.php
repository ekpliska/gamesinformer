<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Жанры
 *
 * @property int $id
 * @property string $name_genre
 * @property integer $isRelevant
 */
class Genre extends ActiveRecord {

    public static function tableName() {
        return 'genre';
    }

    public function rules() {
        return [
            [['name_genre'], 'required', 'message' => 'Поле не заполнено'],
            [['name_genre'], 'string', 'max' => 255],
            [['isRelevant'], 'integer'],
            [['isRelevant'], 'default', 'value' => 0],
            [['name_genre'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Уникакльный идентификатор',
            'name_genre' => 'Жанр',
            'isRelevant' => 'Является актуальным',
        ];
    }
    
    public function getGamePlatformReleases() {
        return $this->hasMany(GameGenre::className(), ['genre_id' => 'id']);
    }
}
