<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\models\TopGames;

/**
 * Жанры
 *
 * @property int $id
 * @property string $name_genre
 * @property integer $isRelevant
 * @property string $description
 * @property string $cover
 * @property string $youtube
 * @property integer $is_used_filter
 */
class Genre extends ActiveRecord {

    public $image_cover;
    public $game_ids;

    public static function tableName() {
        return 'genre';
    }

    public function rules() {
        return [
            [['name_genre'], 'required', 'message' => 'Поле не заполнено'],
            [['name_genre', 'cover', 'youtube'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['isRelevant', 'is_preview_youtube', 'is_used_filter'], 'integer'],
            [['isRelevant', 'is_preview_youtube'], 'default', 'value' => 0],
            [['is_used_filter'], 'default', 'value' => 1],
            [['name_genre'], 'unique'],
            [['youtube'], 'url', 'message' => 'Вы указали некорректный  url адрес'],
            [['image_cover'], 'file', 'extensions' => 'png, jpg, jpeg'],
            ['game_ids', 'safe'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->cover;

        $file = UploadedFile::getInstance($this, 'image_cover');
        
        if ($file && !$this->is_preview_youtube) {
            $this->cover = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/genres_cover/' . time() . '.' . $this->cover->extension;
            $this->cover->saveAs($dir . $file_name);
            $this->cover = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        } elseif ($this->youtube && $this->is_preview_youtube) {
            $youtube = $this->youtube;
            $pos = strpos($youtube, 'watch?v=');
            if ($pos) {
                $youtube_code = substr($youtube, $pos + 8);
                $this->cover = "https://img.youtube.com/vi/{$youtube_code}/hqdefault.jpg";
            }
        }

        return parent::beforeSave($insert);
    }
    
    public function getTopGames() {
        return $this->hasMany(TopGames::className(), ['type_characteristic_id' => 'id']);
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
            'is_used_filter' => 'Показывать в фильтрах',
            'is_preview_youtube' => 'Использовать обложку из youtube-ролика',
        ];
    }
    
    public function getGamePlatformReleases() {
        return $this->hasMany(GameGenre::className(), ['genre_id' => 'id']);
    }
}
