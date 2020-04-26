<?php

namespace api\modules\v1\controllers;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ServerErrorHttpException;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;

use api\modules\v1\models\Game;

class GameController extends Controller {

    public function behaviors() {        
        return [
//            'authenticator' => [
//                'authMethods' => [
//                    HttpBasicAuth::className(),
//                    HttpBearerAuth::className(),
//                ]
//            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
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
        $games = new Game();
        return $games->find()->all();
    }
    
    public function actionView($id) {
        $game = Game::findOne((int)$id);
        
        if (!$game) {
            return [];
        }
        
        return Game::findOne($id);
    }
    
    public function verbs() {
        return [
            'index' => ['get'],
            'view' => ['get'],
        ];
    }

}
