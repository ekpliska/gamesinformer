<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "series".
 *
 * @property int $id
 * @property string $series_name
 * @property string|null $description
 * @property string|null $image
 * @property int|null $enabled
 *
 * @property GameSeries[] $gameSeries
 */
class Series extends ActiveRecord {

    public $image_file;
    public $game_ids;
    
    public static function tableName() {
        return 'series';
    }

    public function rules() {
        return [
            [['series_name'], 'required'],
            [['series_name'], 'string', 'max' => 70],
            [['enabled'], 'integer'],
            [['description'], 'string', 'max' => 1000],
            [['image'], 'string', 'max' => 255],
            [['image_file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            ['game_ids', 'safe'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->image;

        $file = UploadedFile::getInstance($this, 'image_file');
        
        if ($file) {
            $this->image = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/series/' . time() . '.' . $this->image->extension;
            $this->image->saveAs($dir . $file_name);
            $this->image = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        }

        return parent::beforeSave($insert);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'series_name' => 'Серия',
            'description' => 'Описание',
            'image' => 'Изображения',
            'enabled' => 'Показывать в мобильном приложении',
            'image_file' => 'Изображения',
        ];
    }

    public function getGameSeries() {
        return $this->hasMany(GameSeries::className(), ['series_id' => 'id']);
    }
}
