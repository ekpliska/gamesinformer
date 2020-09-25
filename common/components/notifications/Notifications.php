<?php

namespace common\components\notifications;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Game;
use common\models\Series;
use common\models\FavoriteSeries;
use common\models\Favorite;
use common\models\TokenPushMobile;
use common\components\firebasePush\FirebaseNotifications;

class Notifications {
    
    // Игра из серии получила статус "релиз"
    const SERIES_TYPE = 'SERIES_TYPE';
    // Выход игры из избранного
    const GAME_FAVORITE_TYPE = 'GAME_FAVORITE_TYPE';
    // Выход ААА игры
    const AAA_GAME_TYPE = 'AAA_GAME_TYPE';
    // Раздачи
    const SHARES_TYPE = 'SHARES_TYPE';
    // О нас напоминание (Новости)
    const NEWS_TYPE = 'NEWS_TYPE';
    
    private $_type;
    private $_user_ids = [];
    private $_user_ids_is_send = [];
    private $_game;
    private $_series;
    private $_notification;

    public function __construct($type, Game $game = null, Series $series = null, $other = []) {
        
        $this->_type = $type;
        $this->_game = $game;
        $this->_series = $series;
        
        // Формируем список ID пользоватейлей, у которых есть игра в избранном, у игры есть серия, но серия не в избранном
        $users_ids_by_series = [];
        
        switch ($type) {
            case self::SERIES_TYPE:
                if ($series == null || $game == null) {
                    throw new ErrorException('Ошибка передачи параметров. Параметр $series является обязательным.');
                }
                $favorite_series_list = FavoriteSeries::find()->where(['series_id' => $series->id])->asArray()->all();
                $this->_user_ids = ArrayHelper::getColumn($favorite_series_list, 'user_uid');
                $this->_notification = $this->messageBySeries();
                break;
            case self::GAME_FAVORITE_TYPE:
                if ($game == null) {
                    throw new ErrorException('Ошибка передачи параметров. Параметр $series является обязательным.');
                }
                if ($series != null) {
                    $favorite_series_list = FavoriteSeries::find()->where(['series_id' => $series->id])->asArray()->all();
                    $users_ids_by_series = ArrayHelper::getColumn($favorite_series_list, 'user_uid');
                }
                $favorite_game_list = Favorite::find()->where(['game_id' => $game->id])->asArray()->all();
                
                if (count($favorite_series_list) > count($favorite_game_list)) {
                    $this->_user_ids = array_diff($favorite_series_list, $favorite_game_list);
                } else {
                    $this->_user_ids = array_diff($favorite_game_list, $favorite_series_list);
                }
                $this->_notification = $this->messageByGame();
                break;
            case self::AAA_GAME_TYPE:
                if ($game == null) {
                    throw new ErrorException('Ошибка передачи параметров. Параметр $game является обязательным.');
                }
                $users = User::find()->where(['aaa_notifications' => 1])->asArray()->all();
                $this->_user_ids = ArrayHelper::getColumn($users, 'id');
                $this->_notification = $this->messageByAAAGame();
                break;
            case self::SHARES_TYPE:
                $users = User::find()->where(['is_shares' => 1])->asArray()->all();
                $this->_user_ids = ArrayHelper::getColumn($users, 'id');
                $this->_notification = $this->messageByShares($other['games_list']);
                break;
            case self::NEWS_TYPE:
                $users = User::find()
                    ->where([
                        'AND',
                        ['is_time_alert' => 1],
                        ['time_alert' => $other['time']],
                        ['IN', 'days_of_week', $other['day']],
                    ])
                    ->asArray()
                    ->all();
                $this->_user_ids = ArrayHelper::getColumn($users, 'id');
                $this->_notification = $this->messageByNews();
            default:
                $this->_user_ids = [];
                break;
        }
    }
    
    public function createNotification() {
        if ($this->_user_ids == null) {
            return false;
        }

        $tokens = TokenPushMobile::findAll(['in', 'user_uid', $this->_user_ids]);
        $token_ids = ArrayHelper::getColumn($tokens, 'token');
        $notes = new FirebaseNotifications();
        $notes->sendNotification($token_ids, $this->_notification, null, [
            'game_id' => $this->_game ? $this->_game->id : null,
            'series_id' => $this->_series ? $this->_series->id : null,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ]);
        
    }
    
    private function messageBySeries() {
        if ($this->_game == null || $this->_series == null) {
            return "В избранной серии пополнение! Встречаем!";
        }
        return "В серии {$this->_series->series_name} пополнение! Встречаем {$this->_game->title}!";
    }
    
    private function messageByGame() {
        if ($this->_game == null) {
            return "Вы ждали и Вы дождались! Встречаем релиз игры из Избранного!";
        }
        return "Вы ждали и Вы дождались! Встречаем {$this->_game->title}!";
    }
    
    private function messageByAAAGame() {
        if ($this->_game == null) {
            return "На это стоит обратить внимание! Выход AAA-игры!";
        }
        return "На это стоит обратить внимание! {$this->_game->title}!";
    }
    
    private function messageByShares($games_list) {
        if ($games_list == null) {
            return "Обновился список раздач!";
        }
        $list = mb_strimwidth($games_list, 0, 30, '...');
        return "Сейчас бесплатно - {$list}";
    }
    
    private function messageByNews() {
        return "Время пришло!";
    }
    
}