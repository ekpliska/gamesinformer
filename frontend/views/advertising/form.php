<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-advertising',
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
    <div class="row">
    <div class="col-lg-12">
        <?= $form->field($model, 'title')->input('text')->label() ?>
    </div>
    <div class="col-lg-12">
        <?= $form->field($model, 'description')->textarea(['rows' => 5])->label() ?>
    </div>
    <div class="col-lg-7">
        <?= $form->field($model, 'youtube')->input('text')->label() ?>
        <?= $form->field($model, 'is_preview_youtube')->checkbox()->label(false) ?>
        <?= $form->field($model, 'image')->input('file')->label() ?>
    </div>
    <div class="col-lg-5">
        <?php if (!empty($model->preview)) : ?>
            <div class="text-center">
                <img 
                    src="<?= 'http://api.gamenotificator.net' . $model->preview ?>" 
                    class="img-thumbnail" alt="preview" 
                    onerror="this.onerror=null;this.src='<?= $model->preview ?>';">
            </div>
        <?php endif; ?>
    </div>
    </div>
</div>

<div class="modal-footer" style="border: none;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>