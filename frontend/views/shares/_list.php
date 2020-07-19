<?php

use yii\helpers\Html;
?>

<div class="col-md-3 share_item">
    <img src="<?= $model->cover ? $model->coverImage : 'https://placehold.it/400x350?text=NO_PREVIEW' ?>" alt="Cover" class="cover-news" style="width:100%">
    <div class="share_description">
        <div class="share_item_date">
            <?= Yii::$app->formatter->asDate($model->date_start, 'medium') ?>
            <br />
            <?= Yii::$app->formatter->asDate($model->date_end, 'medium') ?>
            <span class="share_item_btn">
                <?=
                    Html::a('<span class="glyphicon glyphicon-file"></span>' , ['shares/update', 'id' => $model->id], [
                        'data-toggle' => 'modal',
                        'data-target' => '#share-update',
                        'onclick' => "$('#share-update .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
                    ]);
                ?>
                <?=
                    Html::a('<span class="glyphicon glyphicon-remove"></span>' , ['shares/delete', 'id' => $model->id]);
                ?>
            </span>
        </div>
        <?= mb_strimwidth($model->description, 0, 70, '...') ?>
    </div>
    <span class="share_type"><?= $model->type ?></span>
</div>