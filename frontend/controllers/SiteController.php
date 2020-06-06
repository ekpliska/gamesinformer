<?php

namespace frontend\controllers;

use frontend\models\form\LoginForm;
use frontend\models\searchFrom\GameSearch;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Game;

/**
 * Site controller
 */
class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['logout', 'signup', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                    ],
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Игры
     */
    public function actionIndex() {

        if (Yii::$app->user->isGuest) {
            return $this->redirect('site/login');
        }

        $this->layout = '@frontend/views/layouts/main';

        $searchModel = new GameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $waiting_publish = Game::getWaitingPublish();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'waiting_publish' => $waiting_publish,
        ]);
    }

    /**
     * Авторизация
     */
    public function actionLogin() {

        $this->layout = '@frontend/views/layouts/login-layouts';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Выход
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
