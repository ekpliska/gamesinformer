<?php

namespace console\controllers;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use common\models\Game;
use common\models\TempData;

/**
 * Формирование временных данных
 */
class TempController extends Controller {

    /**
     * Формирование тегов для тегирования новостей
     */
    public function actionTags() {
        $games = Game::find()->asArray()->all();
        $game_tags = [];
        foreach ($games as $game) {
            $tags = explode('; ', $game['tags']); 
            $game_tags = array_merge($game_tags, $tags);
        }
        
        var_dump($game_tags);
    }

}
