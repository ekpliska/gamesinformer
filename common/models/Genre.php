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
 * @property string $description
 * @property string $cover
 * @property string $youtube
 */
class Genre extends ActiveRecord {


    public $image_cover;

    public static function tableName() {
        return 'genre';
    }

    public function rules() {
        return [
            [['name_genre'], 'required', 'message' => 'Поле не заполнено'],
            [['name_genre', 'cover', 'youtube'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['isRelevant'], 'integer'],
            [['isRelevant'], 'default', 'value' => 0],
            [['name_genre'], 'unique'],
            [['image_cover'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'Уникальный идентификатор',
            'name_genre' => 'Жанр',
            'isRelevant' => 'Является актуальным',
            'description' => 'Описание',
            'cover' => 'Обложка',
            'image_cover' => 'Обложка',
            'youtube' => 'Ссылка на YouTube',
        ];
    }
    
    public function getGamePlatformReleases() {
        return $this->hasMany(GameGenre::className(), ['genre_id' => 'id']);
    }
}
