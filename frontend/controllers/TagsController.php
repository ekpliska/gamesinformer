<?php

namespace frontend\controllers;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Tag;
use frontend\models\form\TagForm;
use common\models\Game;
use frontend\models\searchFrom\TagSearch;

/**
 * Tags controller
 */
class TagsController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new TagForm();
        $game_list = Game::find()->orderBy(['title' => SORT_DESC])->all();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result = $model->save();
            if (!$result['sucess']) {
                Yii::$app->session->setFlash('error', ['message' => $result['error']]);
                return $this->redirect(Yii::$app->request->referrer);
            }
            Yii::$app->session->setFlash('success', ['message' => 'Тег успешно добавлен!']);
            return $this->redirect(['index']);
        }
        
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'game_list' => ArrayHelper::map($game_list, 'id', 'title'),
        ]);
    }
    
    public function actionDelete($id) {
        $model = Tag::findOne($id);

        if ($id == null || !$model) {
            Yii::$app->session->setFlash('error', ['message' => 'Тег не найден']);
            return $this->redirect(['/tags']);
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', ['message' => 'Извините, во время удаления тега произошла ошибка. Попробуйте обновить страницу и повторите действие еще раз']);
        } else {
            Yii::$app->session->setFlash('success', ['message' => 'Тег был успешно удален!']);
        }

        return $this->redirect(['/tags']);
    }

}
