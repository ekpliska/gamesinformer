<?php

namespace api\modules\v1\controllers;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\rest\ActiveController;
use api\modules\v1\models\News;
use api\modules\v1\models\NewsSearch;
use api\modules\v1\models\User;
use common\models\NewsViews;

class NewsController extends ActiveController {
    
    public $modelClass = 'api\modules\v1\models\News';
    
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
        $user = $this->checkAuthUser();
        $user_id = $user ? $user['id'] : null;
        $searchModel = new NewsSearch();
        return $searchModel->search(Yii::$app->request->queryParams, $user_id);
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
        $user = $this->checkAuthUser();
        if ($user) {
            $add_view = new NewsViews();
            $add_view->user_id = $user['id'];
            $add_view->news_id = $news->id;
            $add_view->save(false);
        }
        
        return $news;
    }
    
    private function checkAuthUser() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->asArray()->one();
            return $user;
        }
    }
    
    public function verbs() {
        parent::verbs();
        return [
            'index' => ['GET'],
            'view' => ['GET'],
        ];
    }
    
}
