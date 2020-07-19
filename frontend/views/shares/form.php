<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-shares',
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-7">
                <?=
                    $form->field($model, 'type_share')
                        ->dropDownList($type_list, ['prompt' => 'Выбрать...'])
                        ->label();
                ?>
                <?= $form->field($model, 'link')->input('text')->label() ?>
                <?= $form->field($model, 'description')->textarea(['rows' => 7])->label() ?>
            </div>
            <div class="col-md-5">
                <?php if (!$model->isNewRecord && !empty($model->cover)) : ?>
                    <div class="text-center">
                        <?=
                            Html::img($model->coverImage, [
                                'alt' => 'logo',
                                'class' => 'img-thumbnail',
                                'style' => 'width: 50%;',
                                'onerror' => 'this.onerror=null;this.src=https://placehold.it/400x350?text=NO_COVER'
                            ])
                        ?>
                    </div>
                <?php endif; ?>
                <?= $form->field($model, 'image_cover')->input('file')->label() ?>
                <?= $form->field($model, 'date_start')
                        ->textInput([
                            'type' => 'date', 
                            'value' => $model->isNewRecord ? date('Y-m-d', strtotime('now')) : date('Y-m-d', strtotime($model->date_start))
                        ]); 
                ?>
                <?= $form->field($model, 'date_end')
                        ->textInput([
                            'type' => 'date', 
                            'value' => $model->isNewRecord ? date('Y-m-d', strtotime('now')) : date('Y-m-d', strtotime($model->date_end))]); 
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer row" style="border: none;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>