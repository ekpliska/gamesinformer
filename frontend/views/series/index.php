<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Метаданные';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Серии 
        <?= Html::a('Добавить серию', ['series/new'], ['class' => 'btn btn-sxx']); ?>
    </h3>
    
    <?=
        GridView::widget([
            'dataProvider' => $series,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'series_name',
                [
                    'label' => 'Статус',
                    'format' => 'raw',
                    'value' => function($data) {
                        return $data->enabled 
                                ? '<span class="label label-info">Доступно</span>' 
                                : '<span class="label label-default">Недоступно</span>';
                    },
                ],
                [
                    'label' => 'Игры',
                    'format' => 'raw',
                    'value' => function($data) {
                        $games = $data->gameSeries;
                        $text = '<ul>';
                        foreach ($games as $game) {
                            $text .= '<li>' . $game->game->title . '</li>';
                        }
                        return $text . '</ul>';
                    },
                    'contentOptions'=>['style' => 'font-size: 12px; width: 420px;'],    
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:50px;'],
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['series/update', 'id' => $model->id]);
                        },
                    ],
                ],
            ],
        ]);
    ?>
</div>