<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Test controller
 */
class TestController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'daily'],
                'rules' => [
                    [
                        'actions' => ['index', 'daily'],
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

}
