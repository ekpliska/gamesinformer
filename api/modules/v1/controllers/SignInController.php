<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v1\models\SignInForm;

class SignInController extends Controller {

    public function behaviors() {        
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => $this->verbs(),
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
            ],
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
        ];
    }
    
    public function actionIndex() {
        $model = new SignInForm();
        $model->load(Yii::$app->request->bodyParams, '');
        
        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors)
            ];
        }
 
        if ($token = $model->auth()) {
            return [
                'success' => true,
                'token' => $token
            ];
        }
        
        Yii::$app->response->statusCode = 500;
        return [
            'success' => false,
            'errors' => [],
        ];
    }
    
    public function verbs() {
        return [
            'index' => ['POST'],
        ];
    }

}
