<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\searchFrom\GenreSearch;
use \common\models\Genre;

/**
 * Genre controller
 */
class GenreController extends Controller {

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

    /**
     * Метаданные
     */
    public function actionIndex() {

        $search_genres = new GenreSearch();
        $genres = $search_genres->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'genres' => $genres,
        ]);
    }

    public function actionNew() {
        $model = new Genre();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/genre');
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
        
        $model = Genre::findOne($id);

        if (!$model) {
            return $this->redirect('/genre');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                return $this->redirect('/genre');
            }
        }
        return $this->renderAjax('form', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        
        $model = Genre::findOne($id);
        $name = $model->name_genre;
        
        if (!$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Запись не найдена']);
            return $this->redirect('/genre');
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Ошибка удаления записи']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => "Жанр {$name} был успешно удален"]);
        }
        return $this->redirect('/genre');

    }

}
