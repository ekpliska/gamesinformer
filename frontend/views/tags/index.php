<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ActiveForm;


$this->title = 'Метаданные';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Теги
    </h3>
    
    <div class="panel panel-success">
        <div class="panel-heading">Добавить тег</div>
        <div class="panel-body">
            <?php
                $form = ActiveForm::begin([
                    'id' => 'form-tag',
                    'enableAjaxValidation' => false,
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
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-md-5">
                    <?= $form->field($model, 'tag_name')->input('text')->label() ?>
                </div>
                <div class="col-md-5">
                    <?=
                        $form->field($model, 'game_id')
                            ->dropDownList($game_list, ['prompt' => 'Выбрать игру из списка...'])
                            ->label();
                    ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Тег',
                'format' => 'raw',
                'attribute'=>'tag_name',
                'value' => function($data) {
                    return $data->tag->name;
                },
            ],
            [
                'label' => 'Игра',
                'format' => 'raw',
                'attribute'=>'game_id',
                'filter' => $game_list,
                'value' => function($data) {
                    $list = $data->gameList;
                    $result = ArrayHelper::getColumn($list, 'title');
                    return implode($result, ', ');
                },
                'contentOptions' => ['style' => 'font-size: 12px; width: 320px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'headerOptions' => ['style' => 'width:50px;'],
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['tags/delete', 'id' => $model->id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div> 