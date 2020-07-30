<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\YouTubeRss;
use common\models\YoutubeVideos;

/**
 * Rss youtube controller
 */
class RssYoutubeController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $rss_list = YouTubeRss::find()->all();
        $data_provider = new ActiveDataProvider([
            'query' => YoutubeVideos::find()->orderBy('published DESC'),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index', [
            'rss_list' => $rss_list,
            'data_provider' => $data_provider,
        ]);
    }
    
}
