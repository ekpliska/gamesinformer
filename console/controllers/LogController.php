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
        $current_date->add(\DateInterval::createFromDateString('-1 days'));
        AppLogs::deleteAll(['<=', 'created_at', $current_date->format('Y-m-d H:i:s')]);
    }

}
