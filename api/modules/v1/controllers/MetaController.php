<?php

namespace api\modules\v1\controllers;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\Platform;
use common\models\Genre;

/**
 * Мета данные
 */
class MetaController extends Controller {
    
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
    
    public function actionPlatforms() {
        return Platform::find()->all();
    }
    
    public function actionGenres() {
        return Genre::find()->all();
    }
    
    public function verbs() {
        return [
            'platforms' => ['GET'],
            'genres' => ['GET'],
        ];
    }
    
}
