<?php

namespace console\controllers;
use yii\console\Controller;
use common\models\AppLogs;
use yii\db\Expression;

/**
 * Логи
 */
class LogController extends Controller {

    /**
     * Удаление устаревших логов
     */
    public function actionClear() {
        $current_date = new \DateTime('NOW');
        AppLogs::deleteAll(new Expression(' date(created_at) = date_add(curdate(), INTERVAL - 7 DAY) '));
    }

}
