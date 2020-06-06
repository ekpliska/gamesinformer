<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Новая серия';
$this->params['breadcrumbs'][] = ['label' => 'Серии', 'url' => ['series/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="game-create">

    <?php
        $form = ActiveForm::begin([
            'id' => 'form-series',
            'enableClientValidation' => false,
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>

    <div class="row">

        <?php if (Yii::$app->session->hasFlash('success')) : ?>
            <?php $message = Yii::$app->session->getFlash('success')['message'] ?>
            <div class="alert-message" data-notification-status="success">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')) : ?>
            <?php $message = Yii::$app->session->getFlash('error')['message'] ?>
            <div class="alert-message" data-notification-status="error">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="col-md-7">

            <?= $form->field($model, 'series_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 15]) ?>

            <?= $form->field($model, 'enabled')->checkbox()->label(false) ?>

            <?= $form->field($model, 'image_file')->fileInput(['accept' => 'image/*']) ?>

        </div>

        <div class="col-md-5">
            <div class="panel panel-info">
                <div class="panel-heading">Игры</div>
                <div class="panel-body">
                    <?=
                    $form->field($model, 'game_ids')->widget(Select2::classname(), [
                        'data' => $games,
                        'model' => $model,
                        'options' => [
                            'placeholder' => 'Выберите игру из списка ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => true,
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>