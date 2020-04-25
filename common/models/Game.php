<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Игры
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $series
 * @property string $release_date
 * @property string $publish_at
 * @property int|null $published
 * @property string $cover
 * @property string $website
 * @property string $youtube
 * @property string $youtube_btnlink
 * @property string|null $twitch
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property GameGenre[] $gameGenres
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Game extends ActiveRecord {

    public static function tableName() {
        return 'game';
    }

    public function rules() {
        return [
            [
                ['title', 'release_date', 'publish_at', 'cover', 'website', 'youtube', 'youtube_btnlink'],
                'required',
                'message' => 'Данное поле должно быть заполнено'],
            [['description'], 'string'],
            [['release_date', 'publish_at', 'created_at', 'updated_at'], 'safe'],
            [['published'], 'integer'],
            [['title'], 'string', 'max' => 170],
            [['series', 'cover', 'website', 'youtube', 'youtube_btnlink', 'twitch'], 'string', 'max' => 255],
            [
                ['website', 'youtube', 'youtube_btnlink', 'twitch'], 
                'url',
                'message' => 'Вы указали некорректный  url адрес'],
        ];
    }

    /**
     * Связь с жанрами
     */
    public function getGameGenres() {
        return $this->hasMany(GameGenre::className(), ['game_id' => 'id']);
    }

    /**
     * Связь с релизами на платформе
     */
    public function getGamePlatformReleases() {
        return $this->hasMany(GamePlatformRelease::className(), ['game_id' => 'id']);
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Название игры',
            'description' => 'Описание',
            'series' => 'Серия игр',
            'release_date' => 'Дата выхода',
            'publish_at' => 'Опубликовать игру',
            'published' => 'Опубликовано',
            'cover' => 'Изображение',
            'website' => 'Сайт игры',
            'youtube' => 'YouTube',
            'youtube_btnlink' => 'Ссылка для кнопки YouTube',
            'twitch' => 'Twitch',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
    
}
