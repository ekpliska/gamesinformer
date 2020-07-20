<?php

namespace api\modules\v3\controllers;
use yii\rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use api\modules\v3\models\Platform;
use api\modules\v3\models\Genre;
use api\modules\v3\models\RssChannel;

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
        $platforms = Platform::find()->where(['is_used_filter' => 1])->orderBy(['name_platform' => SORT_ASC])->all();
        foreach ($platforms as $platform) {
            $this->removeKeys($platform);
        }
        return $platforms;
    }
    
    public function actionGenres() {
        $genres = Genre::find()->where(['is_used_filter' => 1])->orderBy(['name_genre' => SORT_ASC])->all();
        foreach ($genres as $genre) {
            $this->removeKeys($genre);
        }
        return $platforms;
    }
    
    public function actionRss() {
        return RssChannel::find()->orderBy(['rss_channel_name' => SORT_DESC])->all();
    }

    private function removeKeys($obj) {
        if (empty($obj)) {
            return false;
        }
        return unset(
            $obj->description, 
            $obj->youtube, 
            $obj->cover,
            $obj->top_games,
        );
    }
    
    public function verbs() {
        return [
            'platforms' => ['GET'],
            'genres' => ['GET'],
        ];
    }
    
}
