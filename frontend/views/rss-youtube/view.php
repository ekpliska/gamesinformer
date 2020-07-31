<h4 class="modal-title"><?= $model->title ?></h4>
<div class="modal-body">
    <p class="text-info">
        <a href="<?= $model->link ?>" target="_blank">Видео</a>
    </p>
    <?= $model->description ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
</div>