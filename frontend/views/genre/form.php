<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
    
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-genre',
        'validateOnChange' => false,
        'validateOnBlur' => false,
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
            <?= $form->field($model, 'name_genre')->input('text') ->label() ?>
        </div>
        <div class="col-lg-12">
            <?= $form->field($model, 'isRelevant')->checkbox()->label(false) ?>
        </div>
    </div>

<div class="modal-footer" style="border: none;">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
    </div>

<?php ActiveForm::end(); ?>