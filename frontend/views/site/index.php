<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Game Notificator';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="col-md-3 site-index__options">
        <?= Html::a('Добавить публикацию', ['game/index'], ['class' => 'new_game_link btn-success']) ?>
        Ждут публикацию
    </div>
    <div class="col-md-9 site-index__table">
        <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    'release_date',
                    [
                        'attribute' => 'published',
                        'content' => function($data){
                            return $data ? 'Опубликовано' : 'Ждет публикации';
                        }
                    ],
                    [
                        'attribute' => 'website',
                        'format' => 'raw',
                        'content' => function($data){
                            return Html::a('Перейти', $data->website, ['target' => '_blank']);
                        }
                    ],
                    [
                        'attribute' => 'youtube',
                        'format' => 'raw',
                        'content' => function($data){
                            return Html::a('Перейти', $data->youtube, ['target' => '_blank']);
                        }
                    ],
                    [
                        'attribute' => 'twitch',
                        'format' => 'raw',
                        'content' => function($data){
                            return Html::a('Перейти', $data->twitch, ['target' => '_blank']);
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['game/update', 'id' => $model->id]);
                            },
                        ],
                    ],
                ],
            ]);
        ?>
    </div>
</div>
