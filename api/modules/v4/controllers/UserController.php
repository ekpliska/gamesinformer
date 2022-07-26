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
use api\modules\v4\models\User;
use api\modules\v4\models\forms\EditProfile;
use api\modules\v4\models\forms\ChangePassword;

/**
 * Профиль пользователя
 */
class UserController extends Controller {
    
    public function behaviors() {

        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['index', 'update', 'change-password', 'subscribe', 'unsubscribe'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['index', 'update', 'reset-password', 'subscribe', 'unsubscribe'],
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
        return $this->getUserProfile();
    }
    
    public function actionUpdate() {
        $user = $this->getUserProfile();
        $model = new EditProfile($user);
        $model->load(Yii::$app->request->bodyParams, '');
        if ($model->save() && $model->validate()) {
            return [
                'success' => true,
            ];
        } else {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors)
            ];
        }
    }
    
    public function actionChangePassword() {
        $user = $this->getUserProfile();
        $model = new ChangePassword($user);
        $model->load(Yii::$app->request->bodyParams, '');
        
        if (!$model->validate()) {
            Yii::$app->response->statusCode = 422;
            return [
                'success' => false,
                'errors' => $model->getErrorSummary($model->errors),
            ];
        }
        
        if ($result = $model->changePassword()) {
            return ['success' => true];
        }
        
        Yii::$app->response->statusCode = 500;
        return [
            'success' => false,
            'errors' => [],
        ];
        
    }

    /**
     * Метод отключен
     */
    public function actionSubscribe() {
        Yii::$app->response->statusCode = 404;
        return ['success' => false];

//        $user = $this->getUserProfile();
//        return ['success' => $user->subscribe()];
    }

    /**
     * Метод отключен
     */
    public function actionUnsubscribe() {
        Yii::$app->response->statusCode = 404;
        return ['success' => false];

//        $user = $this->getUserProfile();
//        return ['success' => $user->unSubscribe()];
    }

    /**
     * Дата закрытия приложения
     * @return bool[]
     */
    public function actionTurnOffApplication($date = null) {
        try {
            $_date = $date ? \Yii::$app->formatter->asDatetime($date, 'yyyy-MM-dd hh:mm:ss') : null;
            $user = $this->getUserProfile();
            if ($user && $user->setLogoutDate($_date)) {
            return ['success' => true];
        }

        } catch (\Exception $ex) {
            return [
                'success' => false,
                'message' => 'Неверый формат даты',
            ];
        }
    }

    /**
     * Выход из приложения
     * @return bool[]
     */
    public function actionLogout() {
        $user = $this->getUserProfile();
        if ($user && $user->setLogoutDate()) {
            return ['success' => true];
        } catch (\Exception $ex) {
            return [
                'success' => false
            ];
        }
        return ['success' => false];
    }

    private function getUserProfile() {
        return User::findOne(Yii::$app->user->id);
    }
    
    public function verbs() {
        return [
            'index' => ['GET'],
            'update' => ['POST'],
            'change-password' => ['POST'],
            'subscribe' => ['GET'],
            'unsubscribe' => ['GET'],
            'turn-off-application' => ['GET'],
            'logout' => ['GET'],
        ];
    }

}
