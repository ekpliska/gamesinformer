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
                <img src="<?= $user->photo ? $user->photo : 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png' ?>" class="avatar img-circle img-thumbnail" alt="avatar">
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
<!--                <li class="list-group-item text-right"><span class="pull-left"><strong>Shares</strong></span> 125</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Likes</strong></span> 13</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Posts</strong></span> 37</li>
                <li class="list-group-item text-right"><span class="pull-left"><strong>Followers</strong></span> 78</li>-->
            </ul>

        </div><!--/col-3-->
        <div class="col-sm-9">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Общее</a></li>
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
            </div>
        </div>
    </div>
</div>
