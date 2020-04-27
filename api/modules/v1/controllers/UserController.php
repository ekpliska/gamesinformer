<?php

namespace api\modules\v1\controllers;
use yii\rest\Controller;

/**
 * Профиль пользователя
 */
class UserController extends Controller {
    
    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['index', 'update', 'reset-password'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'update', 'reset-password'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

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
    
    public function actionIndex() {
        return ['profile'];
    }
    
    public function actionUpdate() {
        return ['update profile'];
    }
    
    public function actionResetPassword() {
        return ['reset password'];
    }
    
    public function verbs() {
        return [
            'index' => ['GET'],
            'update' => ['POST'],
            'reset-password' => ['POST'],
        ];
    }

}
