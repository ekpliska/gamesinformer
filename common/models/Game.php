<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\Favorite;
use common\models\Comments;
use common\models\TagLink;
use common\components\notifications\Notifications;
use common\models\GameLikes;

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
 * @property int $is_prepare
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
            [['series', 'cover', 'website', 'youtube', 'youtube_btnlink', 'twitch'], 'string', 'max' => 255],
            [
                ['website', 'youtube', 'youtube_btnlink', 'twitch'], 
                'url',
                'message' => 'Вы указали некорректный  url адрес'],
            
            [['genres_list', 'cover_file'], 'safe'],
            
            [['cover_file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['series_id'], 'safe'],
            [['is_prepare'], 'default', 'value' => 0],
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
        
        if ($this->series_id) {
            $series = Series::findOne(['id' => $this->series_id]);
            $game_series = ArrayHelper::getColumn($series->gameSeries, 'game_id');
            if (!in_array($this->id, $game_series)) {
                $game = Game::findOne(['id' => $this->id]);
                $notification_series = new Notifications(Notifications::SERIES_TYPE, $game, $series);
                $notification_series->createNotification();
                
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
    
    /**
     * Связь с лайками
     */
    public function getGameLikes() {
        return $this->hasMany(GameLikes::className(), ['game_id' => 'id']);
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
    
    public function checkLike() {
        if (!$this->_user) {
            return false;
        }
        $like = GameLikes::find()->andWhere(['AND', ['user_id' => $this->_user->id], ['game_id' => $this->id]])->asArray()->one();
        if (!$like) {
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
    
    public function checkUser() {
        if (!$this->_user) {
            return false;
        }
        return true;
    }

    public function checkSubscribe() {
        if (!$this->_user) {
            return 'no auth';
        }
        // if (!$this->_user->is_subscription) {
        //     return 'no sub';
        // }
        return true;
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
    
    public function like() {
        if (!$this->_user) {
            return false;
        }
        
        $user_like = GameLikes::find()->where(['AND', ['user_id' => $this->_user->id], ['game_id' => $this->id]])->one();

        if ($user_like) {
            return $user_like->delete() ? true : false;
        }
        
        $add_like = new GameLikes();
        $add_like->user_id = $this->_user->id;
        $add_like->game_id = $this->id;
        return $add_like->save() ? true : false;
    }

    /**
     * Список вышедших игры из избранных игр и серий
     * с даты последнего входа пользователя
     */
    public function getReleasesGamesByFavouriteCollection() {
        $today_releases = [];
        $other_releases = [];

        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $user_id = $this->_user->id;
        $logout_date = $this->_user->logout_at ?
            new \DateTime($this->_user->logout_at, new \DateTimeZone('Europe/Moscow')) :
            $current_date;

        $favourite_game_ids = Favorite::getGamesByUserId($user_id);
        $favourite_game_ids_by_series = FavoriteSeries::getGamesByUserId($user_id);

        $game_ids = ArrayHelper::merge($favourite_game_ids, $favourite_game_ids_by_series);

        if (count($game_ids)) {
            $today_releases = Game::find()
                ->where(['IN', 'id', $game_ids])
                ->andWhere(['publish_at' => $current_date->format('Y-m-d 00:00:00')])
                ->orderBy(['publish_at' => SORT_DESC])
                ->asArray()
                ->all();

            if ($current_date->diff($logout_date)->d > 1) {
                $other_releases = Game::find()
                    ->where(['IN', 'id', $game_ids])
                    ->andWhere([
                        'between',
                        'publish_at',
                        $logout_date->format($this->_user->logout_at ? 'Y-m-d H:i:s' : 'Y-m-d 00:00:00'),
                        $current_date->modify('-1 day')->format('Y-m-d 23:59:59')
                    ])
                    ->orderBy(['publish_at' => SORT_DESC])
                    ->asArray()
                    ->all();
            }
        }

        return [
            'today' => $today_releases,
            'other' => $other_releases,
        ];

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
            'is_prepare' => 'Is Prepare',
        ];
    }
    
}
