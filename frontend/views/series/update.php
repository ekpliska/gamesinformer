<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Серия: ' . $model->series_name;
$this->params['breadcrumbs'][] = ['label' => 'Серии', 'url' => ['series/index']];
$this->params['breadcrumbs'][] = ['label' => $model->series_name, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Просмотр';
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

        <div class="col-md-7">

            <?= $form->field($model, 'series_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 15]) ?>

            <?= $form->field($model, 'enabled')->checkbox()->label(false) ?>

            <?php if (!empty($model->image)) : ?>
                <div class="text-center">
                    <?=
                        Html::img('http://api.gamenotificator.net' . $model->image, [
                            'alt' => 'series cover',
                            'class' => 'img-thumbnail',
                            'onerror' => 'this.onerror=null;this.src=https://placehold.it/400x350?text=NO_COVER'
                        ])
                    ?>
                </div>
            <?php endif; ?>
            
            <?= $form->field($model, 'image_file')->fileInput(['accept' => 'image/*']) ?>
            

        </div>

        <div class="col-md-5">
            <div class="panel panel-info">
                <div class="panel-heading">Игры</div>
                <div class="panel-body">
                    <?php $model->game_ids = $selected_ids; ?>
                    <?=
                        $form->field($model, 'game_ids')->widget(Select2::classname(), [
                            'data' => $games,
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
        <?=
            Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы дейсвительно хотите удалить выбранную серию?',
                    'method' => 'post',
                ],
            ]);
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>