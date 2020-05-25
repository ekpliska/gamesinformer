<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\TokenPushMobile;
use common\components\firebasePush\FirebaseNotifications;

/**
 * Site controller
 */
class TestController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update'],
                'rules' => [
                    [
                        'actions' => ['daily', 'push'],
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
//        $_tokens = TokenPushMobile::find()->andWhere(['enabled' => true])->asArray()->all();
//        $tokens = ArrayHelper::getColumn($_tokens, 'token');
//        $notes = new FirebaseNotifications();
//        $result = $notes->sendNotification(
//                $tokens, [
//                    "badge" => 15,
//                ], ['daily_games_count' => 7]);
//        echo '<pre>';
//        var_dump($result);
    }

    /*
     * Игры, редактирование
     */

    public function actionPush() {
//        $result = TokenPushMobile::send('Состоялся релиз новой игры', 'Название игры');
//        echo '<pre>';
//        var_dump($result);
    }
    
}
