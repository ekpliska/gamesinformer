<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v1\models\PushNotification;

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

        if (!isset($token) || empty($token)) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Отсутствуют обязательный параметр token'
            ];
        }

        $model = new PushNotification();
        if ($model->setPushToken($token)) {
            return [
                'success' => true
            ];
        }

        throw new BadRequestHttpException;
    }

    public function verbs() {
        return [
            'index' => ['GET']
        ];
    }

}
