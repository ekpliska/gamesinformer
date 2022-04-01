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
                <?= $form->field($model, 'rss_channel_name')->input('text')->label() ?>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12">
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
                <?= $form->field($model, 'item_tag')->input('text', [
                        'value' => $model->isNewRecord ? 'item' : $model->item_tag,
                    ])->label()
                ?>
            </div>
        </div>
        <div class="row alert alert-danger">
            <div class="col-md-12">
                <?= $form->field($model, 'root_tag')->input('text', [
                        'value' => $model->isNewRecord ? 'channel' : $model->root_tag,
                    ])->label()
                ?>
            </div>
            <p>
                <strong>Внимание!</strong> Если RSS не содержит в себе корневого тега, данное поле необходимо оставить пустым
                <br />
                Например: По умолчанию для корневого тега используется наименование <code>channel</code>
            </p>
        </div>
        <div class="row alert alert-warning">
            <strong>Теги!</strong> Для настройки сбора новостей по каждой RSS-ленте необходимо указать 
            системе навание XML-тегов, с помощью которых, система будет формировать список новостей.
            <br />
            Например: Для тега заголовка новости <code>title</code>
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