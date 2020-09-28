<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\TokenPushMobile;
use common\components\firebasePush\FirebaseNotifications;
use yii\helpers\ArrayHelper;
use common\components\notifications\Notifications;

/**
 * Test controller
 */
class TestController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['games', 'time-alert', 'daily'],
                'rules' => [
                    [
                        'actions' => ['games', 'time-alert', 'daily'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Для теста пушей
     */
    public function actionDaily() {

        $_tokens = TokenPushMobile::find()->andWhere(['enabled' => true])->asArray()->all();
        $tokens = ArrayHelper::getColumn($_tokens, 'token');
        $notes = new FirebaseNotifications();
        $result = $notes->sendNotification(
                $tokens, [
                    "badge" => 15,
                ], ['daily_games_count' => 7]);
        echo '<pre>';
        var_dump($result);
        die();
    }
    
    /**
     * Для теста пушей
     */
    public function actionGames() {

        $game = \common\models\Game::findOne(1);
        
        $game_series = \common\models\GameSeries::findOne(['game_id' => $game->id]);
        $series = $game_series ? $game_series->series : null;
        $type = Notifications::GAME_FAVORITE_TYPE;
        
        if ($series && $game) {

            $notification_series = new Notifications(Notifications::SERIES_TYPE, $game, $series);
            $notification_series->createNotification();
            
            $notification_game = new Notifications(Notifications::GAME_FAVORITE_TYPE, $game, $series);
            $notification_game->createNotification();
            
        } elseif ($game && $game->is_aaa) {
            // Если игра релиз и она AAA
            $type = Notifications::AAA_GAME_TYPE;
            $notification = new Notifications($type, $game, $series);
            $notification->createNotification();
        }
        
    }
    
    public function actionTimeAlert() {

        $current_date = new \DateTime('NOW');
        $current_day_of_week = \common\models\User::DAYS_OF_WEEK[$current_date->format('N') + 1];
        $current_time = $current_date->format('H:i');
        
        $notification = new Notifications(
            Notifications::NEWS_TYPE, 
            null, null,
            ['day' => $current_day_of_week, 'time' => $current_time]
        );
        
        $notification->createNotification();
        
        echo '<pre>';
        var_dump($current_date);
        echo '<br />';
        var_dump($current_day_of_week);
        echo '<br />';
        var_dump($current_time);
        die();
        
        
    }

}
