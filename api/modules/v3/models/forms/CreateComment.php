<?php

namespace api\modules\v3\models\forms;
use yii\base\Model;
use common\models\Comments;
use common\models\Game;
use api\modules\v3\models\User;

/**
 * Новый комментарий
 */
class CreateComment extends Model {
    
    public $game_id;
    public $message;
    
    public $_user;
    
    public function __construct(User $user, $config = []) {
        $this->_user = $user;
        parent::__construct($config);
    }
    
    public function rules() {
        return [
            [['message'], 'required', 'message' => 'Вы не указали комментарий'],
            [['game_id'], 'required', 'message' => 'Ошибка передачи параметра game_id'],
            ['message', 'string', 'length' => [3, 256]],
            ['game_id', 'checkGame'],
            ['game_id', 'integer'],
        ];
    }
    
    public function checkGame($attribute, $param) {
        
        if (!$this->hasErrors()) {
            $game = Game::find()->where(['id' => $this->$attribute])->one();
            if (!$game) {
                $this->addError($attribute, 'Указанная игра не существует или еще не опубликована');
            }
        }
    }
    
    public function create() {
        if ($this->validate()) {
            $comments = new Comments();
            $comments->game_id = $this->game_id;
            $comments->user_id = $this->_user->id;
            $comments->message = $this->message;
            return $comments->save() ? $comments : false;
        }
        return false;
    }
    
}
