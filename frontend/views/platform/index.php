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
    
    <?=
        GridView::widget([
            'dataProvider' => $platforms,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name_platform',
                [
                    'label' => 'Логотип',
                    'format' => 'raw',
                    'value' => function($data) {
                        if ($data->logo_path) {
                            return Html::img(('http://gamenotificator.net' . $data->logo_path), [
                                'alt' => 'Логотип',
                                'style' => 'width:60px;'
                            ]);
                        }
                    },
                ],
                [
                    'label' => 'Статус',
                    'format' => 'raw',
                    'value' => function($data) {
                        return $data->isRelevant 
                                ? '<span class="label label-info">Актуально</span>' 
                                : '<span class="label label-default">Неактуально</span>';
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:50px;'],
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>', ['platform/update', 'id' => $model->id], [
                                'data-toggle' => 'modal',
                                'data-target' => '#platform-edit',
                                'onclick' => "$('#platform-edit .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['platform/delete', 'id' => $model->id]);
                        },
                    ],
                ],
            ],
        ]);
    ?>
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