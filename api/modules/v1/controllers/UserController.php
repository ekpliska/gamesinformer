<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\rest\Controller;
//use yii\filters\AccessControl;
//use yii\filters\auth\HttpBasicAuth;
//use yii\filters\auth\HttpBearerAuth;
//use yii\web\ServerErrorHttpException;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v1\models\SignupFrom;

class UserController extends Controller {

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

    public function actionSignUp() {
        $model = new SignupFrom();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($model->validate()) {
            return $model;
        }
        return $model;
    }
    
    public function actionLogin() {
        return ['actionLogin'];
    }
    
    public function verbs() {
        return [
            'sign-up' => ['post'],
            'login' => ['post'],
        ];
    }

}
