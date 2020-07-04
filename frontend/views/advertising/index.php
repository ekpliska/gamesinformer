<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;


$this->title = 'Реклама';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Реклама 
        <?= Html::a('Добавить запись', ['advertising/new'], [
            'class' => 'btn btn-sxx',
            'data-toggle' => 'modal',
            'data-target' => '#advertising-add',
            'onclick' => "$('#advertising-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
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
                   'title',
                   [
                       'class' => 'yii\grid\ActionColumn',
                       'template' => '{update} {delete}',
                       'headerOptions' => ['style' => 'width:50px;'],
                       'buttons' => [
                           'update' => function ($url, $model) {
                               return Html::a(
                                   '<span class="glyphicon glyphicon-pencil"></span>', ['advertising/update', 'id' => $model->id], [
                                   'data-toggle' => 'modal',
                                   'data-target' => '#advertising-edit',
                                   'onclick' => "$('#advertising-edit .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                                   ]
                               );
                           },
                           'delete' => function ($url, $model) {
                               return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['advertising/delete', 'id' => $model->id]);
                           },
                       ],
                   ],
               ],
           ]);
    ?>
    
</div>

<?php 
Modal::begin([
    'id' => 'advertising-add',
    'header' => 'Добавить запись',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php 
Modal::begin([
    'id' => 'advertising-edit',
    'header' => 'Редактировать запись',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>