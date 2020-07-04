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
            [['title', 'description', 'preview', 'youtube'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->preview;

        $file = UploadedFile::getInstance($this, 'image');
        
        if ($file) {
            $this->preview = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/uploads/advertising/' . time() . '.' . $this->preview->extension;
            $this->preview->saveAs($dir . $file_name);
            $this->preview = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
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
            'is_preview_youtube' => 'Использовать превью из youtube-ролика',
            'image' => 'Изображение',
        ];
    }
}
