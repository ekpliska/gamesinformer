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
 *
 * @property GamePlatformRelease[] $gamePlatformReleases
 */
class Platform extends ActiveRecord {

    public $image;
     
    public static function tableName() {
        return 'platform';
    }

    public function rules() {
        return [
            [['name_platform'], 'required', 'message' => 'Поле не заполнено'],
            [['name_platform'], 'string', 'max' => 70],
            [['logo_path'], 'string', 'max' => 255],
            ['isRelevant', 'integer'],
            [['isRelevant'], 'default', 'value' => 0],
            [['name_platform'], 'unique'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg']
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
            'id' => 'Уникакльный идентификатор',
            'name_platform' => 'Название',
            'logo_path' => 'Логотип',
            'isRelevant' => 'Является актуальной',
        ];
    }

    public function getGamePlatformReleases() {
        return $this->hasMany(GamePlatformRelease::className(), ['platform_id' => 'id']);
    }
}
