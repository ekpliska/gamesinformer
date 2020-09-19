<?php

namespace console\controllers;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use common\models\Game;
use common\models\GameSeries;
use common\components\firebasePush\FirebaseNotifications;
use common\models\TokenPushMobile;
use common\models\AppLogs;
use common\components\notifications\Notifications;

/**
 * Отложенный пуликации
 */
class GameController extends Controller {

    public function actionPublish() {

        $games = Game::find()
                ->where(['published' => 0])
                ->all();

        $current_date = strtotime(date('Y-m-d 00:00:00'));
        $new_publishies = 0;

        if (count($games)) {
            foreach ($games as $game) {
                $release_date = strtotime($game->publish_at);
                if ($current_date == $release_date) {
                    $game->published = true;
                    $game->save(false);
                    $this->sendNotification($game);
                    $new_publishies++;
                }
            }
        }

        if ($new_publishies > 0) {
            $_tokens = TokenPushMobile::find()->andWhere(['enabled' => true])->asArray()->all();
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
            $notes = new FirebaseNotifications();
            $notes->sendNotification(
                $tokens, null, ['badge' => $new_publishies], ['daily_games_count' => $new_publishies]
            );

            $new_log = new AppLogs();
            $new_log->value_1 = "Обновлен статус ОПУБЛИКОВАНО у игр, их количество, {$new_publishies}";
            $new_log->save(false);
        }
    }
    
    /**
     * Рассылка уведомлений о релизе избранных игр
     * @param type $game
     * @return boolean
     */
    private function sendNotification($game) {
        if (!$game) {
            return false;
        }
        
        $game_series = GameSeries::findOne(['game_id' => $game->id]);
        $series = $game_series ? $game_series->series : null;
        $type = Notifications::GAME_FAVORITE_TYPE;
        
        if ($series) {
            // Если игра релиз и входит в серию
            $type = Notifications::SERIES_TYPE;
        } elseif ($game && $game->is_aaa) {
            // Если игра релиз и она AAA
            $type = Notifications::AAA_GAME_TYPE;
        } else {
            // Если игра релиз и без серии
            $type = Notifications::GAME_FAVORITE_TYPE;
        }
        
        $notification = new Notifications($type, $game, $series);
        $notification->createNotification();
        
    }

}
