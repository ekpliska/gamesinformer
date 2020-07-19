<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use common\models\Platform;
use common\models\Game;
use common\models\TopGames;

/**
 * Platfrom controller
 */
class PlatformController extends Controller {

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

        $dataProvider = new ActiveDataProvider([
            'query' => Platform::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);

    }
    
    public function actionNew() {
        $model = new Platform();
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
                            $top_game = new TopGames();
                            $top_game->type_characteristic = TopGames::TYPE_CHARACTERISTIC_PALFORM;
                            $top_game->type_characteristic_id = $model->id;
                            $top_game->game_id = $game_id;
                            $top_game->save(false);
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Платформа успешно создана!']);
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

        $model = Platform::findOne($id);

        if ($id == null || !$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Платформа не найдена']);
            return $this->redirect(['/platform']);
        }

        $games = ArrayHelper::map(Game::find()->asArray()->all(), 'id', 'title');
        $selected_ids = [];
        if ($model->topGames) {
            $selected_ids = ArrayHelper::getColumn($model->topGames, 'game_id');
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if (!$model->save()) {
                    var_dump($model->errors); die();
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз!']);
                    return $this->redirect(Yii::$app->request->referrer);
                }
                if ($model->topGames) {
                    foreach ($model->topGames as $item) {
                        $item->delete();
                    }
                }
                if (is_array($model->game_ids) && count($model->game_ids) > 0) {
                    foreach ($model->game_ids as $game_id) {
                        $top_game = new TopGames();
                        $top_game->type_characteristic = TopGames::TYPE_CHARACTERISTIC_PALFORM;
                        $top_game->type_characteristic_id = $model->id;
                        $top_game->game_id = $game_id;
                        $top_game->save(false);
                    }
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Информация о платформе была успешно обновлена!']);
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
