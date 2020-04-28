<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
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
        
        $behaviors = parent::behaviors();

//        $behaviors['authenticator']['only'] = ['index', 'view', 'create', 'update', 'delete'];
//        $behaviors['authenticator']['authMethods'] = [
//            HttpBasicAuth::className(),
//            HttpBearerAuth::className(),
//        ];
//
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//            'only' => ['index', 'view', 'create', 'update', 'delete'],
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
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }
    
}
