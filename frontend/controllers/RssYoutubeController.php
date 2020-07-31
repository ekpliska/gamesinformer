<?php

namespace frontend\controllers;
use common\models\RssChannel;
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
        $rss_list = RssChannel::find()->where(['type' => RssChannel::TYPE_YOUTUBE])->all();
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

    public function  actionNew() {
        $model = new RssChannel(['scenario' => RssChannel::SCENARIO_FOR_YOUTUBE_RSS]);
        $type_list = RssChannel::getTypesList();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/rss-youtube');
            }
        }
        return $this->renderAjax('/rss/form-youtube', [
            'model' => $model,
            'type_list' => $type_list,
        ]);
    }

}
