<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Comments;

/**
 * Comments controller
 */
class CommentsController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($game_id = null) {
        $chat_game = Comments::find()->groupBy('game_id')->all();
        $default_game_id = ($game_id == null && count($chat_game) > 0) ? $chat_game[0]->game->id : $game_id;
        $comments = Comments::findOne(['game_id' => $default_game_id]);
        
        return $this->render('index', [
            'chat_game' => $chat_game,
            'comments' => $comments
        ]);
    }
    
}
