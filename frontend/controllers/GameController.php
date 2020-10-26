<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Game;
use common\models\Platform;
use common\models\GamePlatformRelease;
use common\models\Genre;
use common\models\GameGenre;
use common\models\Series;
use common\models\GameSeries;
use common\models\Tag;
use common\models\TagLink;

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
        $platforms = ArrayHelper::map(Platform::find()->orderBy(['isRelevant' => SORT_DESC])->all(), 'id', 'name_platform');
        $genres = ArrayHelper::map(Genre::find()->orderBy(['isRelevant' => SORT_DESC])->all(), 'id', 'name_genre');
        $series = ArrayHelper::map(Series::find()->all(), 'id', 'series_name');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $platform_release = Yii::$app->request->post('GamePlatformRelease');
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз!']);
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
                    if (is_array($model->genres_list) && $model->genres_list) {
                        foreach ($model->genres_list as $genre) {
                            $genre_model = new GameGenre();
                            $genre_model->game_id = $model->id;
                            $genre_model->genre_id = (int)$genre;
                            $genre_model->save();
                        }
                    }
                    if (is_array($model->series_id) && $model->series_id) {
                        $game_series = new GameSeries();
                        $game_series->game_id = $model->id;
                        $game_series->series_id = $model->series_id;
                        $game_series->save(false);
                    }
                    if ($model->tag_list) {
                        $tags = explode('; ', $model->tag_list);
                        if (is_array($tags)) {
                            foreach ($tags as $tag) {
                                $add_tag = new Tag();
                                $add_tag->name = str_replace(';', '', $tag);
                                $add_tag->save(false);

                                $add_tag_link = new TagLink();
                                $add_tag_link->type = TagLink::TYPE_LIST[502];
                                $add_tag_link->type_uid = $model->id;
                                $add_tag_link->tag_id = $add_tag->id;
                                $add_tag_link->save(false);
                            }
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Публикация успешно создана!']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'platforms' => $platforms,
            'genres' => $genres,
            'series' => $series,
        ]);
        
    }
    
    /*
     * Игры, редактирование
     */
    public function actionUpdate($id) {
        
        $model = Game::findOne(['id' => $id]);
        
        if ($id == null || !$model) {
            return $this->redirect(['/']);
        }
        
        $platforms = ArrayHelper::map(Platform::find()->orderBy(['isRelevant' => SORT_DESC])->all(), 'id', 'name_platform');
        $genres = ArrayHelper::map(Genre::find()->orderBy(['isRelevant' => SORT_DESC])->all(), 'id', 'name_genre');
        $series = ArrayHelper::map(Series::find()->all(), 'id', 'series_name');
        
        $tags = ArrayHelper::map(Tag::find()->asArray()->all(), 'id', 'name');
        $selected_tag_ids = [];
        if ($model->tagsGame) {
            $selected_tag_ids = ArrayHelper::getColumn($model->tagsGame, 'tag_id');
        }
        
        if (!$model) {
            return $this->redirect(['/']);
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $platform_release = Yii::$app->request->post('GamePlatformRelease');
                
                if (!$model->save()) {
                    Yii::$app->session->setFlash('error', ['message' => 'Извините, при обработке запроса произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз!']);
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    if ($model->gamePlatformReleases) {
                        GamePlatformRelease::deleteAll(['game_id' => $model->id]);
                    }
                    if (is_array($platform_release) && count($platform_release) > 0) {
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
                    if ($model->gameGenres) {
                        GameGenre::deleteAll(['game_id' => $model->id]);
                    }
                    if (is_array($model->genres_list) && count($model->genres_list) > 0) {
                        foreach ($model->genres_list as $genre) {
                            $genre_model = new GameGenre();
                            $genre_model->game_id = $model->id;
                            $genre_model->genre_id = (int)$genre;
                            $genre_model->save();
                        }
                    }
                    if ($model->seriesGame) {
                        GameSeries::deleteAll(['game_id' => $model->id]);
                    }
                    if ($model->series_id) {
                        $game_series = new GameSeries();
                        $game_series->game_id = $model->id;
                        $game_series->series_id = $model->series_id;
                        $game_series->save(false);
                    }
                    
                    if ($model->tagsGame) {
                        foreach ($model->tagsGame as $item) {
                            $item->delete();
                        }
                    }
                    
                    if (is_array($model->tag_list) > 0) {
                        foreach ($model->tag_list as $tag_id) {
                            $tag_link = new TagLink();
                            $tag_link->type = TagLink::TYPE_LIST[502];
                            $tag_link->type_uid = $model->id;
                            $tag_link->tag_id = $tag_id;
                            $tag_link->save(false);
                        }
                    }
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', ['message' => 'Данные публикации успешно обновлены!']);
                return $this->redirect(['update', 'id' => $model->id]);
            } catch (\Exception $ex) {
                $transaction->rollBack();
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'platforms' => $platforms,
            'genres' => $genres,
            'series' => $series,
            'tags' => $tags,
            'selected_tag_ids' => $selected_tag_ids,
        ]);
    }
    
    public function actionDelete($id) {
        
        $game = Game::findOne(['id' => $id]);
        
        if (!$game->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Извините, во время удаления публикации произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => 'Публикация была успешно удалена!']);
        }
        
        return $this->redirect(['/']);
    }


}
