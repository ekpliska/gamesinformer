<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-sm-12">
            <h1>
                <?= $user->username ? $user->username : $user->email ?>
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="text-center">
                <img src="<?= $user->photo ? 'http://api.gamenotificator.net' . $user->photo : 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png' ?>" class="avatar img-circle img-thumbnail" alt="avatar">
            </div>
            <hr />
            <br />

            <div class="panel panel-default">
                <div class="panel-heading">Статус</div>
                <div class="panel-body">
                    <span class="label <?= $user->status ? 'label-success' : 'label-danger' ?>">
                        <?= $user->status ? 'Активен' : 'Заблокирован' ?>
                    </span>
                </div>
            </div>

            <ul class="list-group">
                <li class="list-group-item text-muted">
                    Платформы
                </li>
                <?php if ($user->userPlatforms) : ?>
                <?php foreach ($user->userPlatforms as $item) : ?>
                    <li class="list-group-item text-left">
                        <strong>
                            <?= $item->platform->name_platform ?>
                        </strong>
                    </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div><!--/col-3-->
        <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Общее</a></li>
                <li><a data-toggle="tab" href="#favorite">Избранное</a></li>
                <li><a data-toggle="tab" href="#settings">Настройки</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="home">
                    <hr>
                    <div class="col-xs-6">
                        <label for="first_name">
                            <h4>Имя пользователя:</h4>
                        </label>
                        <p>
                            <?= $user->username ? $user->username : 'Имя пользователя не было задано' ?>
                        </p>
                    </div>
                    <div class="col-xs-6">
                        <label for="first_name">
                            <h4>Email</h4>
                        </label>
                        <p>
                            <?= $user->email ?>
                        </p>
                    </div>
                </div>
                <div class="tab-pane fade" id="favorite">
                    <ul class="list-group">
                        <?php if (count($user->userFavorite) > 0) : ?>
                            <?php foreach ($user->userFavorite as $game) : ?>
                                <li class="list-group-item">
                                    <?= $game->game->title ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            Избранное отсутствует.
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="tab-pane fade" id="settings">
                    <ul class="list-group">
                        <li class="list-group-item setting-tab">
                            Время оповещений
                            <span class="label label-info">
                                <?= $user->time_alert ? Yii::$app->formatter->asTime($user->time_alert, 'short') : 'Не указано' ?>
                            </span>
                        </li>
                        <li class="list-group-item setting-tab">
                            Уведомления о выходе AAA-игр 
                            <span class="label <?= $user->aaa_notifications ? 'label-success' : 'label-warning' ?>">
                                <?= $user->aaa_notifications ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
