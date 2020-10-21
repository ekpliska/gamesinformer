<?php

namespace api\modules\v3\controllers;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\rest\ActiveController;
use api\modules\v3\models\search\GameSearch;
use api\modules\v3\models\Game;

class GameController extends ActiveController {
    
    public $modelClass = 'api\modules\v3\models\Game';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    public function behaviors() {
        
        $behaviors = parent::behaviors();
        
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
        unset($actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
    public function prepareDataProvider() {
        $searchModel = new GameSearch();
        return $searchModel->search(Yii::$app->request->queryParams);

    }
    
    public function actionSpotAaaGame() {
        $games = new Game();
        if (!$games->checkSubscribe()) {
            Yii::$app->response->statusCode = 401;
            return [
                'success' => false,
                'news' => [],
                'error' => ['Ошибка авторизации'],
            ];
        }
        return [
            'success' => true,
            'news' => $games->getPersonalAaaGameList(),
        ];
    }
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'spot-aaa-game' => ['GET'],
        ];
    }
    
}
