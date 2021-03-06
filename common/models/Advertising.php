<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "advertising".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $preview
 * @property string|null $youtube
 * @property string|null $btn_title
 * @property string|null $link
 * @property int|null $is_preview_youtube
 */
class Advertising extends ActiveRecord {
    
    public $image;
    
    public static function tableName() {
        return 'advertising';
    }

    public function rules() {
        return [
            [['title'], 'required'],
            [['is_preview_youtube'], 'integer'],
            [['title', 'description', 'preview', 'youtube', 'link'], 'string', 'max' => 255],
            [['btn_title'], 'string', 'max' => 150],
            [['link', 'youtube'], 'url', 'message' => 'Вы указали некорректный  url адрес'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->preview;

        $file = UploadedFile::getInstance($this, 'image');
        
        if ($file && !$this->is_preview_youtube) {
            $this->preview = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/uploads/advertising/' . time() . '.' . $this->preview->extension;
            $this->preview->saveAs($dir . $file_name);
            $this->preview = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        } elseif ($this->youtube && $this->is_preview_youtube) {
            $youtube = $this->youtube;
            $pos = strpos($youtube, 'watch?v=');
            if ($pos) {
                $youtube_code = substr($youtube, $pos + 8);
                $this->preview = "https://img.youtube.com/vi/{$youtube_code}/hqdefault.jpg";
            }
        }

        return parent::beforeSave($insert);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'preview' => 'Изображение',
            'youtube' => 'Youtube',
            'youtube' => 'Youtube',
            'link' => 'Адрес сайта',
            'btn_title' => 'Заголовок кнопки',
            'is_preview_youtube' => 'Использовать превью из youtube-ролика',
            'image' => 'Изображение',
        ];
    }
}
