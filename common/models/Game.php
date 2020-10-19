<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\Favorite;
use common\models\Comments;
use common\models\Tag;
use common\models\TagLink;

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
 * @property int $is_aaa
 * @property string|null $twitch
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $only_year
 * @property int $tags
 *
 * @property GameGenre[] $gameGenres
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Game extends ActiveRecord {
    
    public $genres_list;
    public $cover_file;
    public $series_id;
    public $tag_list;


    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
    }
    
    public static function tableName() {
        return 'game';
    }

    public function rules() {
        return [
            [
                ['title', 'release_date', 'publish_at', 'youtube', 'youtube_btnlink', 'genres_list'],
                'required',
                'message' => 'Данное поле должно быть заполнено'],
            [['description'], 'string'],
            [['release_date', 'publish_at', 'created_at', 'updated_at', 'tag_list'], 'safe'],
            [['published', 'is_aaa', 'only_year'], 'integer'],
            [['title'], 'string', 'max' => 170],
            [['series', 'cover', 'website', 'youtube', 'youtube_btnlink', 'twitch', 'tags'], 'string', 'max' => 255],
            [
                ['website', 'youtube', 'youtube_btnlink', 'twitch'], 
                'url',
                'message' => 'Вы указали некорректный  url адрес'],
            
            [['genres_list', 'cover_file'], 'safe'],
            
            [['cover_file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['series_id'], 'safe'],
            
        ];
    }
    
    public function beforeSave($insert) {
        
        $current_image = $this->cover;
        
        $file = \yii\web\UploadedFile::getInstance($this, 'cover_file');
        
        if ($file) {
            $this->cover = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/uploads/covers/' . time() . '.' . $this->cover->extension;
            $this->cover->saveAs($dir . $file_name);
            $this->cover = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        } elseif (!$file && $this->cover == null) {
            $youtube = $this->youtube;
            $pos = strpos($youtube, 'watch?v=');
            if ($pos) {
                $youtube_code = substr($youtube, $pos + 8);
                $this->cover = "https://img.youtube.com/vi/{$youtube_code}/hqdefault.jpg";
            }
        }
        
        if ($this->only_year) {
            $this->release_date = \Yii::$app->formatter->asDate($this->release_date, 'yyyy-12-31');
            $this->publish_at = \Yii::$app->formatter->asDate($this->publish_at, 'yyyy-12-31');
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
    
    /**
     * Связь с релизами на платформе
     */
    public function getSeriesGame() {
        return $this->hasMany(GameSeries::className(), ['game_id' => 'id']);
    }
    
    /**
     * Связь комментариями
     */
    public function getComments() {
        return $this->hasMany(Comments::className(), ['game_id' => 'id']);
    }
    
    public function getGenresList() {
        $genres = $this->gameGenres;
        return ArrayHelper::getColumn($genres, function($item) {
            return $item->genre_id;
        });
    }
    
    /**
     * Связь с тегами
     */
    public function getTagsGame() {
        return $this->hasMany(TagLink::className(), ['type_uid' => 'id'], ['type' => TagLink::TYPE_LIST[502]]);
    }
    
    public function afterDelete() {
        parent::afterDelete();
        TagLink::deleteAll(['AND', ['type_uid' => $this->id], ['type' => TagLink::TYPE_LIST[502]]]);
    }

        public static function getWaitingPublish() {
        return Game::find()
                ->where(['published' => false])
                ->orderBy(['publish_at' => SORT_DESC])
                ->asArray()
                ->all();
    }
    
    public function isFavorite() {
        if (!$this->_user) {
            return false;
        }

        $favorite = Favorite::find()->andWhere(['AND', ['user_uid' => $this->_user->id], ['game_id' => $this->id]])->asArray()->one();
        if (!$favorite) {
            return false;
        }

        return true;
    }
    
    public function getGamePlatformReleasesList() {
        $platforms = $this->gamePlatformReleases;
        $result = [];
        if ($platforms) {
            foreach ($platforms as $platform) {
                $result[] = [
                    'id' => $platform->platform_id,
                    'name' => $platform->platform->name_platform,
                    'date_platform_release' => $platform->date_platform_release,
                    'logo_path' => $platform->platform->logo_path,
                ];
            }
        }
        usort($result, function($value_f, $value_s) {
            if (strtotime($value_f['date_platform_release']) == strtotime($value_s['date_platform_release'])) {
                return 0;
            }
            return (strtotime($value_f['date_platform_release']) > strtotime($value_s['date_platform_release'])) ? -1 : 1;
        });
        return $result;
    }
    
    public function getGameGenresList() {
        $geners = $this->gameGenres;
        $result = [];
        if ($geners) {
            foreach ($geners as $gener) {
                $result[] = [
                    'id' => $gener->genre->id,
                    'name' => $gener->genre->name_genre
                ];
            }
        }
        return $result;
    }
    
    public function getCommentsList() {
        $comments = $this->comments;
        $result = [];
        if ($comments) {
            foreach ($comments as $comment) {
                $result[] = [
                    'id' => $comment->id,
                    'message' => $comment->message,
                    'user' => [
                        'username' => $comment->user->username,
                        'email' => $comment->user->email,
                        'photo' => $comment->user->photo,
                    ],
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            }
        }
        return $result;
    }

    public function getGameSeries() {
        $series = $this->seriesGame;
        $result = [];
        if ($series) {
            foreach ($series as $item) {
                $result[] = [
                    'id' => $item->series_id,
                    'series_name' => $item->series->series_name,
                    'description' => $item->series->description,
                    'image' => $item->series->image,
                ];
            }
        }
        return $result;
    }
    
    public function getGameTagsList() {
        $tags = $this->tagsGame;
        $result = [];
        if ($tags) {
            foreach ($tags as $item) {
                $result[] = $item->tag->name;
            }
        }
        return $result;
    }
    
    private function checkAuthUser() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->one();
            return $user;
        }
        return false;
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Название игры',
            'description' => 'Описание',
            'series' => 'Серия игр',
            'series_id' => 'Серия игр',
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
            'cover_file' => 'Обложка',
            'is_aaa' => 'AAA',
            'only_year' => 'Точная дата релиза неизвестна',
            'tag_list' => 'Теги',
        ];
    }
    
}
