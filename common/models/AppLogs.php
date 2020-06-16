<?php

namespace common\models;
use Yii;

/**
 * This is the model class for table "app_logs".
 *
 * @property int $id
 * @property string|null $value_1
 * @property string|null $value_2
 * @property string|null $value_3
 */
class AppLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value_1', 'value_2', 'value_3'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value_1' => 'Value 1',
            'value_2' => 'Value 2',
            'value_3' => 'Value 3',
        ];
    }
}