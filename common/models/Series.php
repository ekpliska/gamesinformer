<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "series".
 *
 * @property int $id
 * @property int $series_name
 * @property string|null $description
 * @property string|null $image
 * @property int|null $enabled
 *
 * @property GameSeries[] $gameSeries
 */
class Series extends ActiveRecord {

    public static function tableName() {
        return 'series';
    }

    public function rules() {
        return [
            [['series_name'], 'required'],
            [['series_name', 'enabled'], 'integer'],
            [['description', 'image'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'series_name' => 'Series Name',
            'description' => 'Description',
            'image' => 'Image',
            'enabled' => 'Enabled',
        ];
    }

    public function getGameSeries() {
        return $this->hasMany(GameSeries::className(), ['series_id' => 'id']);
    }
}
