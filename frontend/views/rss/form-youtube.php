<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
    $form = ActiveForm::begin([
        'id' => 'form-rss',
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
            <div class="col-md-4">
                <?= $form->field($model, 'type')
                        ->dropDownList($type_list, ['prompt' => 'Выбрать...'])
                        ->label() ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'channel_id')->input('text')->label() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'rss_channel_name')->input('text')->label() ?>
                <?= $form->field($model, 'site_url')->input('text')->label() ?>
            </div>
        </div>

        <?php if (!$model->isNewRecord) : ?>
        <div class="row alert alert-danger text-center">
            <?=
                Html::a("Удалить новости RSS {$model->rss_channel_name}", ['news/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы дейсвительно хотите все новости текущей RSS ленты?',
                        'method' => 'post',
                    ],
                ])
            ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal-footer row" style="border: none;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>