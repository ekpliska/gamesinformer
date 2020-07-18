<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "shares".
 *
 * @property int $id
 * @property int $type_share
 * @property string|null $description
 * @property string|null $cover
 * @property string|null $link
 * @property string|null $date
 */
class Shares extends ActiveRecord {

    const TYPE_DISCOUNT = [
        'id' => 1,
        'value' => 'Скидки',
    ];
    
    const TYPE_PROMOTION = [
        'id' => 2,
        'value' => 'Акции',
    ];
    
    const TYPE_DISTRIBUTION = [
        'id' => 3,
        'value' => 'Раздачи',
    ];
    
    public static function tableName() {
        return 'shares';
    }

    public function rules() {
        return [
            [['type_share'], 'required'],
            [['type_share'], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['cover', 'link'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type_share' => 'Тип',
            'description' => 'Описание',
            'cover' => 'Обложка',
            'link' => 'Ссылка',
            'date' => 'Дата',
        ];
    }
}