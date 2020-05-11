<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'GameNotificator: Вход';
?>

<div class="login_page">
    <div class="login_page__container">
        <div class="login_page__wrapper">
            <div class="login_page__wrapper__logo">
                <?= Html::img('@web/images/logo.png', ['alt' => 'logo']) ?>
                <a href="https://play.google.com/store/apps/details?id=com.gamenotificator">
                    <?= Html::img('@web/images/button.png', ['alt' => 'logo']) ?>
                </a>
                <a href="mailto:inbox@gamenotificator.net">inbox@gamenotificator.net</a>
            </div>
            <div class="login_page__wrapper__form">
                <h4>GameNotificator</h4>
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'validateOnChange' => false,
                        'validateOnBlur' => false,
                    ]); 
                ?>

                    <?= $form->field($model, 'username')->textInput() ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
