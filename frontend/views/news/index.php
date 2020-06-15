<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;


$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="text-center">
                    RSS ленты
                </h4>
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <?=
                        Html::a('Добавить ленту', ['rss/new'], [
                            'class' => 'btn btn-sxx btn-primary',
                            'data-toggle' => 'modal',
                            'data-target' => '#rss-add',
                            'onclick' => "$('#rss-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                        ]);
                    ?>
                </div>
                <?php if (count($rss_list) > 0): ?>
                    <ul class="list-group">
                    <?php foreach ($rss_list as $rss): ?>
                        <li class="list-group-item">
                            <?=
                                Html::a($rss->rss_channel_name, ['rss/update', 'id' => $rss->id], [
                                    'class' => 'btn btn-sxx btn-link',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#rss-update',
                                    'onclick' => "$('#rss-update .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                                ]);
                            ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['rss/delete', 'id' => $rss->id]); ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <li class="list-empty">
                        Список лент пуст.
                    </li>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <h4 class="text-center">
            Новости
        </h4>
        news list
    </div>
</div>

<?php 
Modal::begin([
    'id' => 'rss-add',
    'header' => 'Добавить ленту',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php 
Modal::begin([
    'id' => 'rss-update',
    'header' => 'Редактировать ленту',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>