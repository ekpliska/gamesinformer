<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tag_link".
 *
 * @property int $id
 * @property string $type
 * @property int $type_uid
 * @property int $tag_id
 *
 * @property Tag $tag
 */
class TagLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'type_uid', 'tag_id'], 'required'],
            [['type_uid', 'tag_id'], 'integer'],
            [['type'], 'string', 'max' => 4],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'type_uid' => 'Type Uid',
            'tag_id' => 'Tag ID',
        ];
    }

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
}
