<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\searchFrom\PlatformSearch;
use common\models\Platform;

/**
 * Platfrom controller
 */
class PlatformController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'new', 'upload', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'new', 'upload', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {

        $search_platforms = new PlatformSearch();
        $platforms = $search_platforms->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'search_platforms' => $search_platforms,
            'platforms' => $platforms,
        ]);
    }
    
    public function actionUpdate($id) {
        $model = Platform::findOne($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/platform');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
        ]);
    }
    
    public function actionNew() {
        $model = new Platform();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/platform');
            }
        }
        return $this->renderAjax('form', [
            'model' => $model,
        ]);
    }
    
    public function actionDelete($id) {

        $model = Platform::findOne($id);
        $name = $model->name_platform;

        if (!$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Запись не найдена']);
            return $this->redirect('/platform');
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления записи']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => "Платформа {$name} была успешно удалена"]);
        }
        return $this->redirect('/platform');
    }

}
