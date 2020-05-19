<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\TokenPushMobile;

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
 * @property string|null $website
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
    
    public $genres_list;
    public $cover_file;

    public static function tableName() {
        return 'game';
    }

    public function rules() {
        return [
            [
                ['title', 'release_date', 'publish_at', 'youtube', 'youtube_btnlink'],
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
            
            [['genres_list', 'cover_file'], 'safe'],
            
            [['cover_file'], 'file', 'extensions' => 'png, jpg, jpeg']
            
        ];
    }
    
    public function beforeSave($insert) {
        
        $current_image = $this->cover;
        
        // Отправляем пуш уведомление, если публикация соотвествует текущей дате
        if ($insert) {
            $current_date = strtotime(date('Y-m-d 00:00:00'));
            $release_date = strtotime($this->publish_at);
            if ($current_date == $release_date) {
                TokenPushMobile::send('Состоялся релиз новой игры', $this->title);
            }
        }
        
        $file = \yii\web\UploadedFile::getInstance($this, 'cover_file');
        
        if ($file) {
            $this->cover = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/uploads/covers/' . time() . '.' . $this->cover->extension;
            $this->cover->saveAs($dir . $file_name);
            $this->cover = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        } elseif (!$file && $this->cover == null) {
            var_dump($this->cover); die();
            $youtube = $this->youtube;
            $pos = strpos($youtube, 'watch?v=');
            if ($pos) {
                $youtube_code = substr($youtube, $pos + 8);
                $this->cover = "https://img.youtube.com/vi/{$youtube_code}/hqdefault.jpg";
            }
        }
        
        return parent::beforeSave($insert);
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
    
    public function getGenresList() {
        $genres = $this->gameGenres;
        return ArrayHelper::getColumn($genres, function($item) {
            return $item->genre_id;
        });
    }
    
    public static function getWaitingPublish() {
        return Game::find()
                ->where(['published' => false])
                ->orderBy(['publish_at' => SORT_DESC])
                ->asArray()
                ->all();
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
            'genres_list' => '',
            'cover_file' => 'Обложка'
        ];
    }
    
}
