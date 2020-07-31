<h4 class="modal-title"><?= $model->title ?></h4>
<div class="modal-body">
    <p class="text-info">
        <?= "Видео: {$model->link}" ?>
    </p>
    <?= $model->description ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>