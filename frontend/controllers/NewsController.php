<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use common\models\RssChannel;
use common\models\News;

/**
 * News controller
 */
class NewsController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'generate', 'delete', 'delete-all'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'delete', 'generate', 'delete-all'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {

        $rss_list = RssChannel::find()->all();
        $data_provider = new ActiveDataProvider([
            'query' => News::find()->orderBy('pub_date DESC'),
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('index', [
            'rss_list' => $rss_list,
            'data_provider' => $data_provider,
        ]);
    }
    
    public function actionView($id) {
        $model = News::findOne($id);
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    public function actionDeleteAll() {
        if (!News::deleteAll()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления новостей']);
        }
        Yii::$app->session->setFlash('success', ['message' => 'Новости были успешно удалены']);
        return $this->redirect('/news');
    }

    public function actionDelete($rss_id) {
        if (!News::deleteAll(['rss_channel_id' => $rss_id])) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления новостей']);
        }
        Yii::$app->session->setFlash('success', ['message' => 'Новости RSS ленты были успешно удалены']);
        return $this->redirect('/news');
    }

}
