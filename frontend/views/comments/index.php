<?php

use yii\helpers\Html;

$this->title = 'Комментарии к играм';
$this->params['breadcrumbs'][] = $this->title;
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
                <div class="panel-body">
                    <ul class="list-group">
                        <?php if (count($chat_game) > 0) : ?>
                            <?php foreach ($chat_game as $game) : ?>
                                <li class="list-group-item">
                                    <?= Html::a($game->game->title, ['comments/index', 'game_id' => $game->game->id]) ?>
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
                    Комментарии к игре: <?= $comments->game->title ?>
                </div>
                <div class="panel-body">
                    <?php foreach ($chat_game as $chat) : ?>
                        <div class="message_chat">
                            <div class="message_chat__photo">
                                <img 
                                    src="<?= $chat->user->photo ? 'http://api.gamenotificator.net' . $chat->user->photo : 'http://ssl.gstatic.com/accounts/ui/avatar_2x.png' ?>" 
                                    alt="avatar">
                            </div>
                            <div class="message_chat__content">
                                <div class="message_chat__content_username">
                                    <?= $chat->user->username ?> | <?= $chat->created_at ?>
                                </div>
                                <div class="message_chat__content_message">
                                    <?= $chat->message ?>
                                </div>
                            </div>
                            <div class="message_chat__control">
                                <?= 
                                    Html::a('<span class="glyphicon glyphicon-trash"></span>', ['comments/delete', 'id' => $chat->id])
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