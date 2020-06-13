<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;


$this->title = 'Метаданные';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Жанры 
        <?= Html::a('Добавить жанр', ['genre/new'], [
            'class' => 'btn btn-sxx',
            'data-toggle' => 'modal',
            'data-target' => '#genre-add',
            'onclick' => "$('#genre-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
        ]); ?>
    </h3>
    
    <?=
        GridView::widget([
               'dataProvider' => $dataProvider,
               'tableOptions' => [
                   'class' => 'table table-striped table-bordered'
               ],
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   'name_genre',
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
           ]);
    ?>
    
</div>

<?php 
Modal::begin([
    'id' => 'genre-add',
    'header' => 'Добавить жанр',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php 
Modal::begin([
    'id' => 'genre-edit',
    'header' => 'Редактировать жанр',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>