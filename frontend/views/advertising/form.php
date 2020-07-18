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
        <div class="col-lg-6">
            <?= $form->field($model, 'link')->input('text')->label() ?>
            <?= $form->field($model, 'youtube')->input('text')->label() ?>
            <?= $form->field($model, 'btn_title')->input('text')->label() ?>
            <?= $form->field($model, 'is_preview_youtube')->checkbox()->label(false) ?>
            <?= $form->field($model, 'image')->input('file')->label() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'description')->textarea(['rows' => 8])->label() ?>
            <?php if (!empty($model->preview)) : 
                $url = $model->is_preview_youtube ? $model->preview : 'http://api.gamenotificator.net' . $model->preview;;
            ?>
                <div class="advertising-preview">
                    <?=
                        Html::img($url, [
                            'alt' => 'logo',
                            'class' => 'img-thumbnail',
                            'onerror' => 'this.onerror=null;this.src=https://placehold.it/400x350?text=NO_COVER'
                        ])
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-12">
            <div class="alert alert-warning">
                <strong>Внимание!</strong>
                Для превью из YouTube ролика обязательно указывайте полную ссылку 
                содержащую <code>watch?v=<КОД_ВИДЕО></code>. 
                Например: <code>https://www.youtube.com/watch?v=BODEmT7OKWg</code>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer" style="border: none;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>