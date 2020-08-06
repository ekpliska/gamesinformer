<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Donation controller
 */
class DonationController extends Controller {
    public function actionIndex() {
        $this->layout = '@frontend/views/layouts/donation-layouts';
        return $this->render('index');
    }
}