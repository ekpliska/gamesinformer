<?php

namespace api\modules\v4\controllers;
use Yii;
use yii\rest\Controller;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\Game;
use api\modules\v4\models\User;
use common\models\Favorite;

/**
 * Избранное
 */
class FavoriteController extends Controller {

    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['index', 'add', 'remove'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'add', 'remove'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON
            ]
        ];

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => $this->verbs(),
        ];

        $behaviors['rateLimiter'] = [
            'class' => RateLimiter::className()
        ];

        return $behaviors;
    }
    
    public function actionIndex() {
        $user = User::findOne(\Yii::$app->user->id);
        return [
            'success' => true,
            'favorite' => $user->userFavoriteGames(),
        ];
    }

    public function actionAdd($id) {
        $game = $this->findGame($id);
        if (!$game) {
            Yii::$app->response->statusCode = 404;
            return [
                'success' => false,
                'errors' => ['Выбранная игра не найдена.'],
            ];
        }

        if (!Favorite::add($game->id)) {
            Yii::$app->response->statusCode = 400;
            return [
                'success' => false,
                'errors' => ['Выбранная игра уже находится с списке избранного'],
            ];
        }
        
        return [
            'success' => true,
        ];
    }

    public function actionRemove($id) {
        $game = $this->findGame($id);
        if (!$game) {
            Yii::$app->response->statusCode = 404;
            return [
                'success' => false,
                'errors' => ['Выбранная игра не найдена.'],
            ];
        }
        if (!Favorite::remove($game->id)) {
            Yii::$app->response->statusCode = 500;
            return [
                'success' => false,
                'errors' => [],
            ];
        }
        
        return [
            'success' => true,
        ];
    }

    private function findGame($id) {
        return Game::findOne($id);
    }

    public function verbs() {
        return [
            'add' => ['GET'],
            'remove' => ['GET'],
        ];
    }

}
