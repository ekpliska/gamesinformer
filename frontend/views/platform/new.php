<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$this->title = 'Новая платформа';
$this->params['breadcrumbs'][] = ['label' => 'Платформы', 'url' => ['platform/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="platform-create">
    <?php
        $form = ActiveForm::begin([
            'id' => 'form-platform',
            'enableAjaxValidation' => false,
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'fieldConfig' => [
                'template' => '<div class="field-modal has-label">{label}{input}{error}</div>',
            ],
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]);
    ?>
    <div class="col-md-7">
        <?= $form->field($model, 'name_platform')->input('text')->label() ?>
        <div class="col-md-6">
            <?= $form->field($model, 'isRelevant')->checkbox()->label(false) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'is_used_filter')->checkbox(['checked' => true])->label(false) ?>
        </div>
        <?= $form->field($model, 'image')->input('file')->label() ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 10])->label() ?>
        <?= $form->field($model, 'youtube')->input('text')->label() ?>
        <?= $form->field($model, 'is_preview_youtube')->checkbox()->label(false) ?>
    </div>

    <div class="col-md-5">
        <?= $form->field($model, 'image_cover')->input('file')->label() ?>
        <div class="panel panel-info">
            <div class="panel-heading">Топ игр для текущей платформы:</div>
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
                    ])->label(false);
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>