<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\models\TopGames;

/**
 * Платформы
 *
 * @property int $id
 * @property string $name_platform
 * @property string $logo_path
 * @property integer $isRelevant
 * @property string $description
 * @property string $cover
 * @property string $youtube
 * @property integer $is_used_filter
 * @property integer $is_preview_youtube
 *
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Platform extends ActiveRecord {

    public $image;
    public $image_cover;
    public $game_ids;
     
    public static function tableName() {
        return 'platform';
    }

    public function rules() {
        return [
            [['name_platform'], 'required', 'message' => 'Поле не заполнено'],
            [['name_platform'], 'string', 'max' => 70],
            [['logo_path', 'cover', 'youtube'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['isRelevant', 'is_used_filter', 'is_preview_youtube'], 'integer'],
            [['isRelevant', 'is_preview_youtube'], 'default', 'value' => 0],
            [['is_used_filter'], 'default', 'value' => 1],
            [['name_platform'], 'unique'],
            [['image', 'image_cover'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['youtube'], 'url', 'message' => 'Вы указали некорректный  url адрес'],
            ['game_ids', 'safe'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->logo_path;
        $current_image_cover = $this->cover;

        $file = UploadedFile::getInstance($this, 'image');
        $file_cover = UploadedFile::getInstance($this, 'image_cover');
        
        if ($file) {
            $this->logo_path = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/platforms_logo/' . time() . '.' . $this->logo_path->extension;
            $this->logo_path->saveAs($dir . $file_name);
            $this->logo_path = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        }
        if ($file_cover && !$this->is_preview_youtube) {
            $this->cover = $file_cover;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/platforms_cover/' . time() . '.' . $this->cover->extension;
            $this->cover->saveAs($dir . $file_name);
            $this->cover = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image_cover));
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

    public function attributeLabels() {
        return [
            'id' => 'Уникальный идентификатор',
            'name_platform' => 'Название',
            'logo_path' => 'Логотип',
            'isRelevant' => 'Является актуальной',
            'description' => 'Описание',
            'cover' => 'Обложка',
            'youtube' => 'Ссылка на YouTube',
            'image' => 'Логотип',
            'image_cover' => 'Обложка',
            'is_used_filter' => 'Показывать в фильтрах',
            'is_preview_youtube' => 'Использовать обложку из youtube-ролика',
        ];
    }

    public function getGamePlatformReleases() {
        return $this
                ->hasMany(GamePlatformRelease::className(), ['platform_id' => 'id'])
                ->where(['type_characteristic' => TopGames::TYPE_CHARACTERISTIC_PALFORM]);
    }
    
    public function getTopGames() {
        return $this->hasMany(TopGames::className(), ['type_characteristic_id' => 'id']);
    }
}
