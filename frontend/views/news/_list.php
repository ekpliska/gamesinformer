<?php

use yii\helpers\Html;
$index++;
?>

<div class="col-md-4 news_item">
    <img src="<?= $model->image ? $model->image : 'https://placehold.it/400x350?text=NO_PREVIEW' ?>" alt="Preview news" class="preview-news" style="width:100%">
    <span class="date_new">
        <?= Yii::$app->formatter->asDate($model->pub_date, 'medium') ?>
    </span>
    <h5>
        <?=
            Html::a($model->title, ['news/view', 'id' => $model->id], [
                'data-toggle' => 'modal',
                'data-target' => '#news-view',
                'onclick' => "$('#news-view .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
            ]);
        ?>
    </h5>
    <span class="rss_channel"><?= $model->rss->rss_channel_name ?></span>
    <span class="remove_news">
        <?=
            Html::a('<span class="glyphicon glyphicon-remove"></span>' , ['news/delete-news', 'id' => $model->id, 'type' => 'news']);
        ?>
    </span>
</div>
<?php if ($index % 3 === 0) : ?>
    <div class="clearfix"></div>
<?php endif; ?>