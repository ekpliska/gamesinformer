<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use common\models\TagLink;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 *
 * @property Game $game
 */
class Tag extends ActiveRecord {
    
    public static function tableName() {
        return 'tag';
    }
    
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Тег',
        ];
    }
}
