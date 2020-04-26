<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Game Notificator';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="col-md-3 site-index__options">
        <div class="panel panel-info">
            <div class="panel-body">
                <?= Html::a('Добавить публикацию', ['game/index'], ['class' => 'new_game_link btn-success']) ?>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">Ждут публикацию</div>
            <div class="panel-body">
                // TODO
            </div>
        </div>
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
                            return $data->published 
                                    ? '<span class="label label-success">Опубликовано</span>' 
                                    : '<span class="label label-default">Ждет публикации</span>';
                        }
                    ],
                    [
                        'attribute' => 'platform',
                        'label' => 'Платформы',
                        'content' => function($data) {
                            $platfroms = $data->gamePlatformReleases;
                            $text = '';
                            foreach ($platfroms as $platfrom) {
                                $text .= '<span class="label label-info">' . $platfrom->platform->name_platform . '</span><br />';
                            }
                            return $text;
                        }
                    ],
                    [
                        'attribute' => 'links',
                        'label' => 'Ссылки',
                        'format' => 'raw',
                        'content' => function($data){
                            return Html::a('Website', $data->website, ['target' => '_blank']) . '<br />' .
                                    Html::a('YouTube', $data->youtube, ['target' => '_blank']) . '<br />' .
                                    Html::a('Twitch', $data->twitch, ['target' => '_blank']);
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
