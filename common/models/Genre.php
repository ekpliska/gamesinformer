<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Жанры
 *
 * @property int $id
 * @property string $name_genre
 */
class Genre extends ActiveRecord {

    public static function tableName() {
        return 'genre';
    }

    public function rules() {
        return [
            [['name_genre'], 'required'],
            [['name_genre'], 'string', 'max' => 255],
            [['name_genre'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name_genre' => 'Name Genre',
        ];
    }
    
    public function getGamePlatformReleases() {
        return $this->hasMany(GameGenre::className(), ['genre_id' => 'id']);
    }
}
