<?php

namespace console\controllers;
use yii\console\Controller;
use common\models\Shares;
use common\components\notifications\Notifications;

/**
 * Оповещение раздач
 */
class SharesController extends Controller
{

    /**
     * Рассылка уведомлений о старте раздач
     * Проверка даты начала у Скидок/Акций/Раздач
     * Проверка даты окончания у Скидок/Акций/Раздач
     * Ежедневно каждый час
     */
    public function actionCheckDateEnd()
    {
        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));

        $shares = Shares::find()->all();
        if (count($shares)) {
            foreach ($shares as $key => $share) {
                $date_end = new \DateTime($share->date_end);
                $date_start = new \DateTime($share->date_start);

                if (($current_date->diff($date_start)->days === 0) &&
                    ($current_date->diff($date_start)->h === 0) &&
                    ($current_date->diff($date_start)->i === 0) &&
                    !$share->is_published
                ) {
                    $share->is_published = 1;
                    $share->save();

                    $games_list[] = $share->game_list;

                    $notification = new Notifications(
                        Notifications::SHARES_TYPE,
                        null, null,
                        ['games_list' => implode(', ', $games_list)]
                    );
                    $notification->createNotification();

                    continue;
                } else if (($current_date->diff($date_end)->days === 0) &&
                    ($current_date->diff($date_end)->h === 0) &&
                    ($current_date->diff($date_end)->i === 0) &&
                    $share->is_published
                ) {
                    $share->is_published = 0;
                    $share->save();
                    continue;
                }
            }
        }
    }
}
