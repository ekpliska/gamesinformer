<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\RssChannel;

/**
 * Rss controller
 */
class RssController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['new', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['new', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionNew() {

        $model = new RssChannel(['scenario' => RssChannel::SCENARIO_FOR_NEWS_RSS]);
        $type_list = RssChannel::getTypesList();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/news');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
            'type_list' => $type_list,
        ]);
    }
    
    public function actionUpdate($id) {

        $model = RssChannel::findOne($id);
        $model->scenario = $model::SCENARIO_FOR_NEWS_RSS;
        $type_list = RssChannel::getTypesList();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return $this->redirect('/news');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
            'type_list' => $type_list,
        ]);
    }
    
    public function actionDelete($id) {

        $model = RssChannel::findOne($id);
        $name = $model->rss_channel_name;

        if (!$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Запись не найдена']);
            return $this->redirect('/news');
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления записи']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => "RSS лента {$name} была успешно удалена"]);
        }
        return $this->redirect('/news');
    }

}
