<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v1\models\User;
use api\modules\v1\models\EditProfile;
use api\modules\v1\models\ChangePassword;

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
        return $model->save() ? ['success' => true] : $model;
    }
    
    public function actionChangePassword() {
        $user = $this->getUserProfile();
        $model = new ChangePassword($user);
        $model->load(Yii::$app->request->bodyParams, '');
        
        if ($result = $model->changePassword()) {
            return $result;
        } else {
            return $model;
        }
        
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
