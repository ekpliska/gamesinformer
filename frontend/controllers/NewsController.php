<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Html;
use common\models\RssChannel;
use common\models\News;

/**
 * News controller
 */
class NewsController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'new', 'upload', 'delete', 'check', 'remove'],
                'rules' => [
                    [
                        'actions' => ['index', 'new', 'upload', 'delete', 'check', 'remove'],
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