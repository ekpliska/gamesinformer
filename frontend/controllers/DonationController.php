<?php

namespace frontend\controllers;
use yii\web\Controller;

/**
 * Donation controller
 */
class DonationController extends Controller {
    public function actionIndex() {
        $this->layout = '@frontend/views/layouts/donation-layouts';
        return $this->render('index');
    }
    
    public function actionSuccess() {
        $this->layout = '@frontend/views/layouts/donation-layouts';
        return $this->render('success');
    }
    
    public function actionFail() {
        $this->layout = '@frontend/views/layouts/donation-layouts';
        return $this->render('fail');
    }
}