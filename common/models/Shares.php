<?php

namespace common\models;
use Yii;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shares".
 *
 * @property int $id
 * @property int $type_share
 * @property string|null $description
 * @property string|null $cover
 * @property string|null $link
 * @property string|null $date_start
 * @property string|null $date_end
 * @property string $game_list
 * @property int $is_published
 */
class Shares extends ActiveRecord {

    const TYPE_DISTRIBUTION = 701;
    const TYPE_FREE_WEEKEND = 702;
    const TYPE_PROMOTION = 703;
    const TYPE_DISCOUNT = 704;
    
    public $image_cover;


    public static function tableName() {
        return 'shares';
    }

    public function rules() {
        return [
            [['type_share'], 'required'],
            [['type_share', 'is_published'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['cover', 'link', 'game_list'], 'string', 'max' => 255],
            [['link'], 'url', 'message' => 'Вы указали некорректный  url адрес'],
            [['image_cover'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['is_published'], 'default', 'value' => 1],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->cover;

        $file = UploadedFile::getInstance($this, 'image_cover');
        
        if ($file) {
            $this->cover = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/shares/' . time() . '.' . $this->cover->extension;
            $this->cover->saveAs($dir . $file_name);
            $this->cover = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        }

        return parent::beforeSave($insert);
    }
    
    public function getType() {
        return $this->type_share ? $this->getTypeList()[$this->type_share] : 'Не определено';
    }
    
    public static function getTypeList() {
        return [
            self::TYPE_DISTRIBUTION => 'Раздачи',
            self::TYPE_FREE_WEEKEND => 'Бесплатные выходные',
            self::TYPE_PROMOTION => 'Акции',
            self::TYPE_DISCOUNT => 'Скидки',
        ];
    }
    
    public function getCoverImage() {
        return 'http://api.gamenotificator.net' . $this->cover;
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type_share' => 'Тип',
            'description' => 'Описание',
            'cover' => 'Обложка',
            'image_cover' => 'Обложка',
            'link' => 'Ссылка',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'game_list' => 'Список игр из раздачи',
            'is_published' => 'Опубликовано',
        ];
    }
}