<?php

namespace frontend\models\form;
use Yii;
use yii\base\Model;
use common\models\Game;
use common\models\Tag;
use common\models\TagLink;

/**
 * Добавление тега
 */
class TagForm extends Model {
    
    public $tag_name;
    public $game_id;
    
    /**
     * Правила валидации
     */
    public function rules() {
        return [
            [['tag_name', 'game_id'], 'required'],
            [['tag_name'], 'string', 'min' => 2, 'max' => 255],
            ['game_id', 'validateGame'],
        ];
    }
    
    /**
     * Проверка указанной игры
     */
    public function validateGame($attribute, $params) {
        if (!$this->hasErrors()) {
            $game = Game::findOne($this->game_id);
            if (!$game) {
                $this->addError($attribute, 'Данной игры  не существует');
            }
        }
    }
    
    public function save() {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $tag = new Tag();
                $tag->name = $this->tag_name;
                if (!$tag->save()) {
                    return ['sucess' => false, 'error' => $tag->firstErrors['name']];
                }
                $tag_link = new TagLink();
                $tag_link->tag_id = $tag->id;
                $tag_link->type = TagLink::TYPE_LIST[502];
                $tag_link->type_uid = $this->game_id;
                
                if (!$tag_link->save()) {
                    return ['sucess' => false, 'error' => $tag_link->firstErrors['name']];
                }
                
                $transaction->commit();
                return ['sucess' => true];
            } catch (Exception $ex) {
                $transaction->rollBack();
            }
        }
    }

    public function attributeLabels() {
        return [
            'tag_name' => 'Тег',
            'game_id' => 'Игра',
        ];
    }

}
