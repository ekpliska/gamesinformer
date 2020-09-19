<?php

namespace console\controllers;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use common\models\Shares;
use common\components\notifications\Notifications;

/**
 * Оповещение раздач
 */
class SharesController extends Controller {

    /**
     * Рассылка уведомлений о старте раздач
     * Ежедневно в 13:00
     */
    public function actionSend() {
        $current_date = new \DateTime('NOW');
        $shares = Shares::find()->where(['>=', 'date_start', $current_date->format('Y-m-d')])->asArray()->all();
        $games_list = ArrayHelper::getColumn($shares, 'game_list');
        
        $notification = new Notifications(
                Notifications::SHARES_TYPE, 
                null, null, 
                ['games_list' => implode(', ', $games_list)]
        );
        $notification->createNotification();
    }

}
