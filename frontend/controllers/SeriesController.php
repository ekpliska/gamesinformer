<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use frontend\models\searchFrom\SeriesSearch;
use common\models\Series;
use common\models\Game;
use common\models\GameSeries;

/**
 * Series controller
 */
class SeriesController extends Controller {

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
        $model = new SeriesSearch();
        $series = $model->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'series' => $series,
        ]);
    }
    
    public function actionNew() {
        $model = new Series();
        
        $games = ArrayHelper::map(Game::find()->asArray()->all(), 'id', 'title');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз!']);
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    $game_ids = $model->game_ids;
                    if (is_array($model->game_ids) && count($game_ids) > 0) {
                        foreach ($game_ids as $game_id) {
                            $game_series = new GameSeries();
                            $game_series->game_id = $game_id;
                            $game_series->series_id = $model->id;
                            $game_series->save(false);
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Серия успешно создана!']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }
        return $this->render('new', [
            'model' => $model,
            'games' => $games,
        ]);
    }
    
    public function actionUpdate($id) {
        
        $model = Series::findOne($id);
        
        if ($id == null || !$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Серия не найдена']);
            return $this->redirect(['/series']);
        }

        $games = ArrayHelper::map(Game::find()->asArray()->all(), 'id', 'title');
        $selected_ids = [];
        if ($model->gameSeries) {
            $selected_ids = ArrayHelper::getColumn($model->gameSeries, 'game_id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз!']);
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    if ($model->gameSeries) {
                        foreach ($model->gameSeries as $item) {
                            $item->delete();
                        }
                    }
                    if (is_array($model->game_ids) && count($model->game_ids) > 0) {
                        foreach ($model->game_ids as $game_id) {
                            $game_series = new GameSeries();
                            $game_series->game_id = $game_id;
                            $game_series->series_id = $model->id;
                            $game_series->save(false);
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Серия успешно создана!']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }
        return $this->render('update', [
            'model' => $model,
            'games' => $games,
            'selected_ids' => $selected_ids,
        ]);
    }
    
    public function actionDelete($id) {
        
        $model = Series::findOne($id);
        
        if ($id == null || !$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Серия не найдена']);
            return $this->redirect(['/series']);
        }
        
        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Извините, во время удаления серии произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => 'Серия была успешно удалена!']);
        }
        
        return $this->redirect(['series/index']);
    }

}
