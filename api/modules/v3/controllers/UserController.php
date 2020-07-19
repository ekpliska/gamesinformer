<?php

namespace api\modules\v3\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v3\models\User;
use api\modules\v3\models\EditProfile;
use api\modules\v3\models\ChangePassword;

/**
 * Профиль пользователя
 */
class UserController extends Controller {
    
    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['index', 'update', 'change-password'];
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
        return $this->getUserProfile();
    }
    
    public function actionUpdate() {
        $user = $this->getUserProfile();
        $model = new EditProfile($user);
        $model->load(Yii::$app->request->bodyParams, '');
        if ($model->save() && $model->validate()) {
            return [
                'success' => true,
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors)
            ];
        }
    }
    
    public function actionChangePassword() {
        $user = $this->getUserProfile();
        $model = new ChangePassword($user);
        $model->load(Yii::$app->request->bodyParams, '');
        
        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors),
            ];
        }
        
        if ($result = $model->changePassword()) {
            return ['success' => true];
        }
        
        Yii::$app->response->statusCode = 500;
        return [
            'success' => false,
            'errors' => [],
        ];
        
    }
    
    private function getUserProfile() {
        return User::findOne(Yii::$app->user->id);
    }
    
    public function verbs() {
        return [
            'index' => ['GET'],
            'update' => ['POST'],
            'change-password' => ['POST'],
        ];
    }

}
