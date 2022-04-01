<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\AppLogs;

/**
 * Logs controller
 */
class LogsController extends Controller {

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

        $query = AppLogs::find()->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteAll() {
        if (AppLogs::deleteAll()) {
            Yii::$app->session->setFlash('error', ['message' => 'Извините, произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => 'Логи успешно очищены!']);
        }
        return $this->redirect('/logs');
    }
    
}
