<?php

use yii\helpers\Html;

$this->title = 'Комментарии к играм';
$this->params['breadcrumbs'][] = $this->title;
$get_game_id = isset(Yii::$app->controller->actionParams['game_id']) ? Yii::$app->controller->actionParams['game_id'] : null;
?>

<div class="comments-index">
    <h3>
        Комментарии к играм 
    </h3>
    
    
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Игры
                </div>
                <div class="panel-body comments-game">
                    <ul class="list-group">
                        <?php if (count($chat_game) > 0) : ?>
                            <?php foreach ($chat_game as $key => $game) : ?>
                                <li class="list-group-item chat <?= ($key == 0 && $get_game_id == null) ? 'active' : ($get_game_id == $game->game_id) ? 'active' : '' ?>">
                                    <?= Html::a($game->game->title, ['comments/index', 'game_id' => $game->game_id]) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">
                                Список пуст.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
            <?php if (count($comments) > 0) : ?>
                <div class="panel-heading">
                    Комментарии к игре: <span class="badge"><?= count($comments) ?></span>
                </div>
                <div class="panel-body comments-game">
                    <?php foreach ($comments as $comment) : ?>
                        <div class="message_chat">
                            <div class="message_chat__photo">
                                <img 
                                    src="<?= $comment->user->photo ? 'http://api.gamenotificator.net' . $comment->user->photo : 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png' ?>" 
                                    alt="avatar">
                            </div>
                            <div class="message_chat__content">
                                <div class="message_chat__content_username">
                                    <?= $comment->user->username ?> | <?= $comment->created_at ?>
                                </div>
                                <div class="message_chat__content_message">
                                    <?= $comment->message ?>
                                </div>
                            </div>
                            <div class="message_chat__control">
                                <?= 
                                    Html::a('<span class="glyphicon glyphicon-trash"></span>', ['comments/delete', 'id' => $comment->id])
                                ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <?= 'Сообщения отсутствуют.' ?>
            <?php endif; ?>
            </div>
        </div>
    </div>    
</div>