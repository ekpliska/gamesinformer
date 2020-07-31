<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Modal;
use common\models\RssChannel;

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="text-center">
                    YouTube новости
                </h4>
            </div>
            <div class="panel-body">
                <div class="text-center">
                    <?=
                        Html::a('Добавить ленту', ['rss-youtube/new'], [
                            'class' => 'btn btn-sxx btn-success',
                            'data-toggle' => 'modal',
                            'data-target' => '#rss-youtube-add',
                            'onclick' => "$('#rss-youtube-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
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
                                    'data-target' => '#rss-youtube-update',
                                    'onclick' => "$('#rss-youtube-update .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
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
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h5 class="text-center">
                    Управление
                </h5>
            </div>
            <div class="panel-body">
                <?php if ($data_provider->totalCount > 0) : ?>
                    <div class="row text-center">    
                        <?=
                        Html::a("Удалить YouTube новости", ['news/delete-all', 'type_rss' => RssChannel::TYPE_YOUTUBE], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы действительно хотите удалить все YouTube новости?',
                                'method' => 'post',
                            ],
                        ])
                        ?>
                    </div>
                <?php else: ?>
                    <div class="row text-center">
                        <?= Html::a('Сгенерировать новости', ['rss-youtube/generate'], ['class' => 'btn btn-sxx btn-primary']) ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <?=
            ListView::widget([
                'dataProvider' => $data_provider,
                'options' => [
                    'tag' => 'div',
                    'class' => 'list-wrapper row',
                    'id' => 'list-wrapper',
                ],
                'layout' => "<span class='summary'>{summary}</span>\n{items}\n{pager}", // выводим постраничную навигацию вначале и в конце списка, общее количесвто элементов и количестов элементов показанных на странице и сам список
                'summary' => 'Показано {count} из {totalCount}', // шаблон для summary
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_list', ['model' => $model, 'index' => $index]);
                },
                'emptyText' => 'Новостей нет',
                'emptyTextOptions' => [// опции для пустого контейнера
                    'tag' => 'p' // добавляем тег абзаца для пустого контейнера
                ],
                'pager' => [// постраничная разбивка
                    'firstPageLabel' => 'Первая', // ссылка на первую страницу
                    'lastPageLabel' => 'Последняя', // ссылка на последнюю странцу
                    'nextPageLabel' => 'Следующая', // ссылка на следующую странцу
                    'prevPageLabel' => 'Предыдущая', // ссылка на предыдущую странцу        
                    'maxButtonCount' => 5, // количество отображаемых страниц
                ],
            ]);
        ?>
    </div>
</div>

<?php
Modal::begin([
    'id' => 'rss-youtube-add',
    'header' => 'Добавить ленту',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php
Modal::begin([
    'id' => 'rss-youtube-update',
    'header' => 'Редактировать ленту',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php
Modal::begin([
    'id' => 'rss-youtube-view',
    'size' => Modal::SIZE_LARGE,
]);

Modal::end();
?>