<?php

namespace api\modules\v2\controllers;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\rest\ActiveController;
use api\modules\v2\models\News;
use api\modules\v2\models\NewsSearch;
use api\modules\v2\models\User;
use common\models\NewsViews;

class NewsController extends ActiveController {
    
    public $modelClass = 'api\modules\v2\models\News';
    
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
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }
    
    public function prepareDataProvider() {
        $searchModel = new NewsSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }
    
    public function actionView($id) {
        $news = News::findOne((int)$id);
        if (!$news) {
            Yii::$app->response->statusCode = 404;
            return [
                'success' => false,
                'errors' => ['Новость не найдена'],
            ];
        }
        // Отмечаем прсмотр
        $news->addViews();
        return $news;
    }
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }
    
}
