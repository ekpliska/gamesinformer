<?php

namespace api\modules\v2\controllers;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v2\models\Platform;
use api\modules\v2\models\Genre;
use api\modules\v2\models\RssChannel;

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
        return Platform::find()->orderBy(['name_platform' => SORT_ASC])->all();
    }
    
    public function actionGenres() {
        return Genre::find()->orderBy(['name_genre' => SORT_ASC])->all();
    }
    
    public function actionRss() {
        return RssChannel::find()->orderBy(['rss_channel_name' => SORT_DESC])->all();
    }
    
    public function verbs() {
        return [
            'platforms' => ['GET'],
            'genres' => ['GET'],
        ];
    }
    
}
