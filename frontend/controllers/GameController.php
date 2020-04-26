<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Game;
use common\models\Platform;
use common\models\GamePlatformRelease;
use common\models\Genre;
use common\models\GameGenre;

/**
 * Site controller
 */
class GameController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update'],
                'rules' => [
                    [
                        'actions' => ['index', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Игры, сохранение
     */
    public function actionIndex() {
        
        $model = new Game();
        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name_platform');
        $genres = ArrayHelper::map(Genre::find()->all(), 'id', 'name_genre');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $platform_release = Yii::$app->request->post('GamePlatformRelease');
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    foreach ($platform_release as $item) {
                        if ($item['platform_id'] == null || $item['date_platform_release'] == null) {
                            continue;
                        }
                        $release_model = new GamePlatformRelease();
                        $release_model->game_id = $model->id;
                        $release_model->platform_id = (int)$item['platform_id'];
                        $release_model->date_platform_release = $item['date_platform_release'];
                        $release_model->save();
                    }
                    if ($model->genres_list) {
                        foreach ($model->genres_list as $genre) {
                            $genre_model = new GameGenre();
                            $genre_model->game_id = $model->id;
                            $genre_model->genre_id = (int)$genre;
                            $genre_model->save();
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Публикация успешно создана']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'platforms' => $platforms,
            'genres' => $genres
        ]);
        
    }
    
    /*
     * Игры, редактирование
     */
    public function actionUpdate($id) {
        if ($id == null) {
            return $this->redirect(['/']);
        }
        
        $model = Game::findOne(['id' => $id]);
        $platforms = ArrayHelper::map(Platform::find()->all(), 'id', 'name_platform');
        $genres = ArrayHelper::map(Genre::find()->all(), 'id', 'name_genre');
        
        if (!$model) {
            return $this->redirect(['/']);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $platform_release = Yii::$app->request->post('GamePlatformRelease');
                
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    if ($platform_release) {
                        if ($model->gamePlatformReleases) {
                            GamePlatformRelease::deleteAll(['game_id' => $model->id]);
                        }
                        foreach ($platform_release as $item) {
                            if ($item['platform_id'] == null || $item['date_platform_release'] == null) {
                                continue;
                            }
                            $release_model = new GamePlatformRelease();
                            $release_model->game_id = $model->id;
                            $release_model->platform_id = (int)$item['platform_id'];
                            $release_model->date_platform_release = $item['date_platform_release'];
                            $release_model->save();
                        }
                    }
                    if ($model->genres_list) {
                        if ($model->gameGenres) {
                            GameGenre::deleteAll(['game_id' => $model->id]);
                        }
                        foreach ($model->genres_list as $genre) {
                            $genre_model = new GameGenre();
                            $genre_model->game_id = $model->id;
                            $genre_model->genre_id = (int)$genre;
                            $genre_model->save();
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Данные публикации успешно обновлены']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'platforms' => $platforms,
            'genres' => $genres
        ]);
    }

}
