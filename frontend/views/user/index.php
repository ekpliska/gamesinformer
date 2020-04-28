<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="col-md-12">
        <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered'
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    'email',
                    'created_at',
                    [
                        'attribute' => 'status',
                        'content' => function($data){
                            return $data->status 
                                    ? '<span class="label label-success">Активен</span>' 
                                    : '<span class="label label-danger">Заблокирован</span>';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['user/view', 'id' => $model->id]);
                            },
                        ],
                    ],
                ],
            ]);
        ?>
    </div>
</div>
