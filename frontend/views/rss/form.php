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
            <div class="col-md-12">
                <?= $form->field($model, 'rss_channel_name')->input('text')->label() ?>
                <?= $form->field($model, 'rss_channel_url')->input('text')->label() ?>
            </div>
        </div>
        <div class="row alert alert-info">
            <div class="col-md-6">
                <?= $form->field($model, 'title_tag')->input('text')->label() ?>
                <?= $form->field($model, 'description_tag')->input('text')->label() ?>
                <?= $form->field($model, 'pub_date_tag')->input('text')->label() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'image_tag')->input('text')->label() ?>
                <?= $form->field($model, 'link_tag')->input('text')->label() ?>
            </div>
        </div>
        <div class="row alert alert-warning">
            <strong>Теги!</strong> Для настройки сбора новостей по каждой RSS-ленте необходимо указать 
            системе навание XML-тегов, с помощью которых, система будет формировать список новостей.
            <br />
            Например: Для тега заголовка новости <code>title</code>
        </div>
        <div class="row alert alert-danger text-center">
            <?php if (!$model->isNewRecord) : ?>
                <?=
                    Html::a("Удалить новости RSS {$model->rss_channel_name}", ['news/delete', 'rss_id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы дейсвительно хотите все новости текущей RSS ленты?',
                            'method' => 'post',
                        ],
                    ])
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal-footer row" style="border: none;">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::button('Отмена', ['class' => 'btn', 'data-dismiss' => 'modal']) ?>
</div>

<?php ActiveForm::end(); ?>