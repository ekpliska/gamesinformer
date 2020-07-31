<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\RssChannel;
use common\models\News;
use console\controllers\YoutubeController as YoutubeControllerConsole;

/**
 * Rss youtube controller
 */
class RssYoutubeController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'new', 'generate'],
                'rules' => [
                    [
                        'actions' => ['index', 'new', 'generate'],
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
            'query' => News::find()->joinWith('rss')->where(['type' => RssChannel::TYPE_YOUTUBE])->orderBy('pub_date DESC'),
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

    public function actionGenerate() {
        $consoleController = new YoutubeControllerConsole('youtube', Yii::$app);
        $consoleController->runAction('load');
        Yii::$app->session->setFlash('success', ['message' => 'Youtube новости были сгенерированы']);
        return $this->redirect('/rss-youtube');
    }

    public function actionView($id) {
        $model = News::findOne($id);
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

}
