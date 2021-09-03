<?php

$days_of_week = json_decode($user->days_of_week);

$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => $user->username ? $user->username : $user->email];
?>

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
                <div class="panel-heading">Дополнительная информация</div>
                <div class="panel-body">
                    <p>
                        Статус 
                        <span class="label <?= $user->status ? 'label-success' : 'label-danger' ?>">
                            <?= $user->status ? 'Активен' : 'Заблокирован' ?>
                        </span>
                    </p>
                    <p>
                        Подписка 
                        <span class="label <?= $user->is_subscription ? 'label-success' : 'label-danger' ?>">
                            <?= $user->is_subscription ? 'Есть' : 'Нет' ?>
                        </span>
                    </p>
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
                        <li class="list-group-item setting-notice-tab">
                            <div class="item">
                                <span>Оповещения</span>
                                <span class="label <?= $user->is_time_alert ? 'label-info' : 'label-default' ?>">
                                    <?= $user->is_time_alert ? 'ВКЛ' : 'ВЫКЛ' ?>
                                </span>
                            </div>
                            <div class="item">
                                <span>Дни недели</span>
                                <?php if (is_array($days_of_week) && count($days_of_week) > 0) : ?>
                                    <div>
                                        <?php foreach ($days_of_week as $day) : ?>
                                        <span class="badge" style="background-color: #337ab7;"><?= $day ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <span>Не указано</span>
                                <?php endif; ?>
                            </div>
                            <div class="item">
                                <span>Время</span>
                                <span class="label label-info">
                                    <?= $user->time_alert ? Yii::$app->formatter->asTime($user->time_alert, 'short') : 'Не указано' ?>
                                </span>
                            </div>
                             
                        </li>
                        <li class="list-group-item setting-tab">
                            Уведомления о выходе AAA-игр 
                            <span class="label <?= $user->aaa_notifications ? 'label-success' : 'label-warning' ?>">
                                <?= $user->aaa_notifications ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                        <li class="list-group-item setting-tab">
                            Уведомления о выходе игр из избранного
                            <span class="label <?= $user->is_favorite_list ? 'label-success' : 'label-warning' ?>">
                                <?= $user->is_favorite_list ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                        <li class="list-group-item setting-tab">
                            Уведомление об изменениях в сериях из избранного
                            <span class="label <?= $user->is_favorite_series ? 'label-success' : 'label-warning' ?>">
                                <?= $user->is_favorite_series ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                        <li class="list-group-item setting-tab">
                            Уведомления о раздачах 
                            <span class="label <?= $user->is_shares ? 'label-success' : 'label-warning' ?>">
                                <?= $user->is_shares ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                        <li class="list-group-item setting-tab">
                            Реклама (Не актуальная настройка)
                            <span class="label <?= $user->is_advertising ? 'label-success' : 'label-warning' ?>">
                                <?= $user->is_advertising ? 'ВКЛ' : 'ВЫКЛ' ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
