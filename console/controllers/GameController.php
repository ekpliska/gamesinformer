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
 * Получение опубликованных игр ночью
 * Ежедневно в 00:00
 */
class GameController extends Controller {

    public function actionPreparePublish() {
        $games = Game::find()
            ->where(['AND', ['published' => 0], ['only_year' => 0], ['is_prepare' => 0]])
            ->all();

        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $current_date = strtotime($date->format('Y-m-d 00:00:00'));

        $new_publishies = 0;

        if (count($games)) {
            foreach ($games as $game) {
                $release_date = strtotime($game->publish_at);
                $diff = ($release_date - $current_date)/3600/24;
                if ($diff == 0) {
                    $game->published = true;
                    $game->is_prepare = true;
                    $game->save(false);
                    $new_publishies++;
                }
            }
        }

        if ($new_publishies > 0) {
            $new_log = new AppLogs();
            $new_log->value_1 = "Обновлен статус ОПУБЛИКОВАНО у игр, их количество, {$new_publishies}";
            $new_log->save(false);
        }
    }


    /**
     * Отправка уведомлений о выходе новых игр
     * Ежедневно в 10:00
     * @throws \Exception
     */
    public function actionSendNoticeByPublish() {

        $games = Game::find()
            ->where(['AND', ['published' => 1], ['is_prepare' => 1]])
                ->all();

        $count_games = count($games);

        if ($count_games) {
            foreach ($games as $game) {
                $game->is_prepare = false;
                $this->sendNotification($game);
                $game->save(false);
            }

            $_tokens = TokenPushMobile::find()->andWhere(['enabled' => true])->asArray()->all();
            $tokens = ArrayHelper::getColumn($_tokens, 'token');
            $notes = new FirebaseNotifications();
            $notes->sendNotification(
                $tokens, null, ['badge' => $count_games], ['daily_games_count' => $count_games]
            );
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

        if ($game && $game->is_aaa) {
            // Если игра релиз и она AAA
            $notification = new Notifications(Notifications::AAA_GAME_TYPE, $game, $series);
            $notification->createNotification();
        }
        
        if ($series) {
            // Если игра имеет серию
            $notification_series = new Notifications(Notifications::SERIES_TYPE, $game, $series);
            $notification_series->createNotification();
        }

        $notification_game = new Notifications(Notifications::GAME_FAVORITE_TYPE, $game, $series);
        $notification_game->createNotification();

        return true;
            
    }

}
