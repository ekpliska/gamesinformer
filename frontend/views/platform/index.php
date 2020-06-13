<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;


$this->title = 'Метаданные';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Платформы 
        <?= Html::a('Добавить платформу', ['platform/new'], [
            'class' => 'btn btn-sxx',
            'data-toggle' => 'modal',
            'data-target' => '#platform-add',
            'onclick' => "$('#platform-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
        ]); ?>
    </h3>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name_platform',
            'logo_path',
            'isRelevant',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => ['style' => 'width:50px;'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', ['genre/update', 'id' => $model->id], [
                                'data-toggle' => 'modal',
                                'data-target' => '#genre-edit',
                                'onclick' => "$('#genre-edit .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['genre/delete', 'id' => $model->id]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<?php 
Modal::begin([
    'id' => 'platform-edit',
    'header' => 'Редактировать платформу',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php 
Modal::begin([
    'id' => 'platform-add',
    'header' => 'Добавить платформу',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>