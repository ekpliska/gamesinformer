<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Modal;


$this->title = 'Халява';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <div class="row">
        <h3>
            Халява
            <?=
                Html::a('Добавить запись', ['shares/new'], [
                    'class' => 'btn btn-sxx btn-link',
                    'data-toggle' => 'modal',
                    'data-target' => '#share-add',
                    'onclick' => "$('#share-add .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                ]);
            ?>
        </h3>
    </div>
    
    <div class="row">
        <div class="col-md-12">
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
                    'emptyText' => 'Нет данных',
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
</div>

<?php
Modal::begin([
    'id' => 'share-add',
    'header' => 'Добавить запись',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>

<?php
Modal::begin([
    'id' => 'share-update',
    'header' => 'Редактировать запись',
    'closeButton' => [
        'class' => 'close modal-close-btn',
    ],
]);

Modal::end();
?>