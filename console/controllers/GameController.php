<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\models\Game;
use common\models\TokenPushMobile;

/**
 * Отложенный пуликации
 */
class GameController extends Controller {
    
    public function actionPublish() {
        
        $games = Game::find()
                ->where(['published' => 0])
                ->all();
        
        $current_date = (date('Y-m-d 00:00:00'));
        $new_publishies = 0;
        
        if (count($games)) {
            foreach ($games as $game) {
                $release_date = ($game->publish_at);
                if ($current_date == $release_date) {
                    $game->published = true;
                    $game->save(false);
                    $new_publishies++;
                }
            }
        }
        
        if ($new_publishies > 0) {
            TokenPushMobile::send(Yii::$app->name, $new_publishies);
        }
        
        
    }
    
}
