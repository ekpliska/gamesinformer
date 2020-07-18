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
        <?= Html::a('Добавить жанр', ['genre/new'], ['class' => 'btn btn-sxx']); ?>
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
                        'label' => 'ТОП игр',
                        'format' => 'raw',
                        'value' => function($data) {
                            $games = $data->topGames;
                            $text = '<ul>';
                            foreach ($games as $game) {
                                $text .= '<li>' . $game->game->title . '</li>';
                            }
                            return $text . '</ul>';
                        },
                        'contentOptions' => ['style' => 'font-size: 12px; width: 420px;'],
                    ],
                    [
                        'label' => 'Свойства',
                        'format' => 'raw',
                        'value' => function($data) {
                            return
                                Html::tag('span', $data->isRelevant ? 'Актуально' : 'Неактуально', ['class' => $data->isRelevant ? 'label label-info' : 'label label-default']) .
                                Html::tag('br') .
                                Html::tag('span', $data->is_used_filter ? 'Используется в фильтрах' : 'Не используется в фильтрах', ['class' => $data->is_used_filter ? 'label label-success' : 'label label-warning']);
                        },
                    ],
                    [
                       'class' => 'yii\grid\ActionColumn',
                       'template' => '{update} {delete}',
                       'headerOptions' => ['style' => 'width:50px;'],
                       'buttons' => [
                           'update' => function ($url, $model) {
                               return Html::a(
                                   '<span class="glyphicon glyphicon-pencil"></span>', ['genre/update', 'id' => $model->id]
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