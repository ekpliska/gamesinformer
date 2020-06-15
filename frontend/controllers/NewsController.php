<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\RssChannel;

/**
 * News controller
 */
class NewsController extends Controller {

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

        $rss_list = RssChannel::find()->all();
        
        return $this->render('index', [
            'rss_list' => $rss_list,
        ]);

    }

}
