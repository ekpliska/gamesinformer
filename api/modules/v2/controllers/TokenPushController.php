<?php

namespace api\modules\v2\controllers;
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
use api\modules\v2\models\PushNotification;
use api\modules\v2\models\User;

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

        $_headers =  getallheaders();
        $headers = array_change_key_case($_headers);
        $user_id = null;
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $auth_token = trim($auth_token, 6);
            $user = User::find()->where(['token' => $auth_token])->asArray()->one();
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
