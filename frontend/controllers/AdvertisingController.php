<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\Advertising;

/**
 * Advertising controller
 */
class AdvertisingController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'new', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'new', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метаданные
     */
    public function actionIndex() {

        $dataProvider = new ActiveDataProvider([
            'query' => Advertising::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionNew() {
        $model = new Advertising();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/advertising');
            }
        }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        return $this->renderAjax('form', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        
        $model = Advertising::findOne($id);

        if (!$model) {
            return $this->redirect('/advertising');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return $this->redirect('/advertising');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        
        $model = Advertising::findOne($id);
        
        if (!$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Запись не найдена']);
            return $this->redirect('/advertising');
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления записи']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => "Запись была успешно удалена"]);
        }
        return $this->redirect('/advertising');

    }

}
