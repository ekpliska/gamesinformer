<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Логи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <h3>
        Логи
        <?= Html::a('Очистить логи', ['logs/delete-all'], ['class' => 'btn btn-danger']); ?>
    </h3>
    
    <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered'
            ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'created_at',
                'value_1',
//                'value_2',
//                'value_3',
            ],
        ]);
    ?>
</div>