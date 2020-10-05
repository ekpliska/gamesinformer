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
                'only' => ['games', 'time-alert', 'daily', 'first', 'series', 'aaa'],
                'rules' => [
                    [
                        'actions' => ['games', 'time-alert', 'daily', 'first', 'series', 'aaa'],
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
    }

}
