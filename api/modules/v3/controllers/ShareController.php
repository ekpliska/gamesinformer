<?php

namespace api\modules\v3\controllers;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\rest\ActiveController;
use api\modules\v3\models\Shares;
use api\modules\v3\models\SharesSearch;

class ShareController extends ActiveController {
    
    public $modelClass = 'api\modules\v3\models\Shares';
    
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
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
    public function actionGetShareTypes() {
        return Shares::getTypeList();
    }


    public function prepareDataProvider() {
        $searchModel = new SharesSearch();
        return $searchModel->search(Yii::$app->request->queryParams);

    }
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET'],
            'get-share-types' => ['GET'],
        ];
    }
    
}
