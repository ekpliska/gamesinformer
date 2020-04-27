<?php

namespace api\modules\v1\controllers;
use Yii;
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

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Game;

class GameController extends ActiveController {
    
    public $modelClass = 'api\modules\v1\models\Game';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

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
    
    public function actions() {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
    public function prepareDataProvider() {
        
        $games = Game::find()
                ->where(['published' => true])
                ->orderBy(['release_date' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $games
        ]);
        
        return $dataProvider;
    }

}
