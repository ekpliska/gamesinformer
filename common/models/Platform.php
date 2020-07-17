<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

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
 *
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Platform extends ActiveRecord {

    public $image;
    public $image_cover;
     
    public static function tableName() {
        return 'platform';
    }

    public function rules() {
        return [
            [['name_platform'], 'required', 'message' => 'Поле не заполнено'],
            [['name_platform'], 'string', 'max' => 70],
            [['logo_path', 'cover', 'youtube'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            ['isRelevant', 'integer'],
            [['isRelevant'], 'default', 'value' => 0],
            [['name_platform'], 'unique'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['image_cover'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->logo_path;

        $file = UploadedFile::getInstance($this, 'image');
        
        if ($file) {
            $this->logo_path = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/platforms_logo/' . time() . '.' . $this->logo_path->extension;
            $this->logo_path->saveAs($dir . $file_name);
            $this->logo_path = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
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
        ];
    }

    public function getGamePlatformReleases() {
        return $this->hasMany(GamePlatformRelease::className(), ['platform_id' => 'id']);
    }
}
