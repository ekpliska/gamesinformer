<?php

namespace frontend\controllers;
use yii\web\Controller;

/**
 * Privacy policy controller
 */
class PrivacyPolicyController extends Controller {

    public function actionPolicy() {
        \Yii::$app->response->sendFile('policy/polisy.docx');
        return 1;
    }
    
    public function actionLicenseAgreement() {
      \Yii::$app->response->sendFile('policy/EULA.docx');
      return 1;
    }
}