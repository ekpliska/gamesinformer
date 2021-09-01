<?php

namespace api\modules\v4\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v4\models\User;
use api\modules\v4\models\forms\CreateComment;

/**
 * Комментарии к играм
 */
class CommentsController extends Controller {
    
    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['create'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create'],
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
    
    public function actionCreate() {
        $user = $this->getUserProfile();
        $model = new CreateComment($user);
        $model->load(Yii::$app->request->bodyParams, '');
        if ($model->create() && $model->validate()) {
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
    
    private function getUserProfile() {
        return User::findOne(Yii::$app->user->id);
    }
    
    public function verbs() {
        return [
            'create' => ['POST'],
        ];
    }

}
