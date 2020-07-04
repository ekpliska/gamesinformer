<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "app_logs".
 *
 * @property int $id
 * @property string|null $value_1
 * @property string|null $value_2
 * @property string|null $value_3
 */
class AppLogs extends ActiveRecord {

    public static function tableName() {
        return 'app_logs';
    }

    public function rules() {
        return [
            [['value_1', 'value_2', 'value_3'], 'string', 'max' => 255],
            ['created_at', 'safe'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'value_1' => 'Комментарий',
            'value_2' => 'Дополнительно',
            'value_3' => 'Дополнительно',
            'created_at' => 'Дата',
        ];
    }
}