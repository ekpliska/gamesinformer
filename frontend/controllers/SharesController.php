<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use common\models\Shares;

/**
 * Shares controller
 */
class SharesController extends Controller {

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

    public function actionIndex() {

        $type_list = Shares::getTypeList();
        $data_provider = new ActiveDataProvider([
            'query' => Shares::find()->orderBy('date DESC'),
            'pagination' => [
                'pageSize' => 24,
            ],
        ]);

        return $this->render('index', [
            'type_list' => $type_list,
            'data_provider' => $data_provider,
        ]);
    }
    
    public function actionNew() {

        $type_list = Shares::getTypeList();
        $model = new Shares();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/shares');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
            'type_list' => $type_list,
        ]);
    }
    
    public function actionUpdate($id) {

        $model = Shares::findOne($id);
        $type_list = Shares::getTypeList();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->save()) {
                return $this->redirect('/shares');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
            'type_list' => $type_list,
        ]);
    }
    
    public function actionDelete($id) {

        $model = Shares::findOne($id);

        if (!$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Запись не найдена']);
            return $this->redirect('/news');
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления записи']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => 'Запись была успешно удалена']);
        }
        return $this->redirect('/shares');
    }
    
}
