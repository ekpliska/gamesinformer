<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
    
?>

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
    <div class="modal-body">
        <div class="col-lg-12">
            <?= $form->field($model, 'name_platform')->input('text') ->label() ?>
        </div>

        <div class="col-lg-12">
            <?= $form->field($model, 'isRelevant')->checkbox()->label(false) ?>
        </div>

        <div class="col-lg-12">
            <?= $form->field($model, 'image')->input('file')->label() ?>
        </div>
    
    </div>

<div class="modal-footer" style="border: none;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>