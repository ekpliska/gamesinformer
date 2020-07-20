<?php

namespace api\modules\v3\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v3\models\forms\SignupFrom;

class SignUpController extends Controller {

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
        $model = new SignupFrom();
        $model->load(Yii::$app->request->bodyParams, '');
        
        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors),
            ];
        }
        
        if ($result = $model->register()) {
            return [
                'success' => true,
                'token' => $result->token,
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
            'index' => ['POST']
        ];
    }

}
