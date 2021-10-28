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
     * Ежедневно каждый час
     */
    public function actionSend() {
        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $shares = Shares::find()->all();
        $games_list = [];
        
        if (count($shares) == 0) {
            return false;
        }

        $count_new_shares = 0;

        foreach ($shares as $key => $item) {
            $date_start = new \DateTime($item->date_start, new \DateTimeZone('Europe/Moscow'));
            $diff = $current_date->diff($date_start);
            $hours = $diff->h + ($diff->days * 24);
            if ($hours == 0) {
                $games_list[] = $item->game_list;
                $count_new_shares++;
            }
        }

        if ($count_new_shares > 0) {
            $notification = new Notifications(
                    Notifications::SHARES_TYPE, 
                    null, null, 
                    ['games_list' => implode(', ', $games_list)]
            );
            $notification->createNotification();
        }
    }

    /**
     * Проверка даты начала у Скидок/Акций/Раздач
     * Проверка даты окончания у Скидок/Акций/Раздач
     * Ежедневно в 20:00
     */
    public function actionCheckDateEnd() {
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $current_date = strtotime($date->format('Y-m-d 00:00:00'));

        $shares = Shares::find()->all();
        if (count($shares)) {
            foreach ($shares as $key => $share) {
                if ($share->is_published) {
                    $date_end = strtotime($share->date_end);
                    $diff_end = ($date_end - $current_date)/3600/24;
                    if ($diff_end <= 0) {
                        $share->is_published = 0;
                        $share->save();
                    }
                } else {
                    $date_start = strtotime($share->date_start);
                    $diff_start = ($date_start - $current_date)/3600/24;
                    if ($diff_start <= 0) {
                        $share->is_published = 1;
                        $share->save();
                    }
                }
            }
        }
    }
}
