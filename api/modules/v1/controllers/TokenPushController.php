<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
//use yii\web\BadRequestHttpException;
//use yii\filters\AccessControl;
//use yii\filters\auth\HttpBasicAuth;
//use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v1\models\PushNotification;
use api\modules\v1\models\User;

/**
 * Профиль пользователя
 */
class TokenPushController extends Controller {

    public function behaviors() {

        $behaviors = parent::behaviors();

//        $behaviors['authenticator']['only'] = ['index'];
//        $behaviors['authenticator']['authMethods'] = [
//            HttpBasicAuth::className(),
//            HttpBearerAuth::className(),
//        ];
//
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['index'],
//            'rules' => [
//                [
//                    'allow' => true,
//                    'roles' => ['@'],
//                ],
//            ],
//        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => $this->verbs(),
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::className()
        ];

        return $behaviors;
    }

    public function actionIndex($token) {

        $headers = getallheaders();
        $user_id = null;
        if (isset($headers['authorization'])) {
            $token = trim(substr($headers['authorization'], 6));
            $user = User::find()->where(['token' => $token])->asArray()->one();
            if ($user) {
                $user_id = $user['id'];
            }
        }

        if (!isset($token) || empty($token)) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'errors' => [
                    'Отсутствуют обязательный параметр token',
                ],
            ];
        }

        $model = new PushNotification();
        if ($model->setPushToken($token, $user_id)) {
            return [
                'success' => true,
            ];
        }

        Yii::$app->response->statusCode = 500;
        return [
            'success' => false,
            'errors' => [],
        ];
    }

    public
            function verbs() {
        return [
            'index' => ['GET']
        ];
    }

}
