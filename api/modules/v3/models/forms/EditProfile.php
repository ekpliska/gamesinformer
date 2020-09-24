<?php

namespace api\modules\v3\models\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Platform;
use common\models\UserPlatform;

/**
 * Редактирование профиля
 */
class EditProfile extends Model {

    const DIR_NAME = 'uploads/user_photo';

    public $username;
    public $photo;
    public $platforms = [];
    public $time_alert;
    public $aaa_notifications;
    public $is_time_alert;
    public $is_advertising;
    public $is_shares;
    public $days_of_week;
    private $_user;

    public function __construct(User $user, $config = []) {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules() {
        return [
            ['username', 'string', 'max' => 70],
            ['photo', 'safe'],
            ['photo', 'checkBase64'],
            ['is_time_alert', 'checkTimeAlert'],
            ['days_of_week', 'checkIsDaysOfWeek'],
            [['platforms', 'days_of_week'], 'safe'],
            [['aaa_notifications', 'is_time_alert', 'is_advertising', 'is_shares'], 'boolean'],
            [['time_alert'], 'time', 'format' => 'php:H:i', 'message' => 'Неверный формат времени 00:00'],
        ];
    }

    public function checkBase64($attribute, $param) {
        if (!$this->hasErrors()) {
            if (!base64_decode($this->photo, true)) {
                $this->addError($attribute, 'Загружаемое изображение не соотвествует формату base64');
            }
        }
    }
    
    public function checkTimeAlert($attribute, $param) {
        if (!$this->hasErrors()) {
            if ($this->is_time_alert && 
                    (!isset($this->time_alert) || !isset($this->days_of_week) || count($this->days_of_week) == 0)
            ) {
                $this->addError($attribute, 'Для включения опции "О Нас" необходимо указать время и дни недели');
            }
        }
    }
    
    public function checkIsDaysOfWeek($attribute, $param) {
        if (!$this->hasErrors()) {
            if (!is_array($this->days_of_week)) {
                $this->addError($attribute, 'Неверный формат дней недели');
            } else {
                foreach ($this->days_of_week as $days) {
                    if (!in_array($days, User::DAYS_OF_WEEK)) {
                        $this->addError($attribute, 'Некорректные дни недели');
                    }
                }  
            }
        }
    }

    public function save() {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        if ($this->photo) {
            $user->photo = $this->uploadImage($this->photo, $user->id);
        }
        if (is_array($this->platforms) && $this->platforms) {
            if ($user->userPlatforms) {
                UserPlatform::deleteAll(['user_id' => $user->id]);
            }
            foreach ($this->platforms as $platform) {
                $user_pl = new UserPlatform();
                if (!Platform::findOne($platform)) {
                    continue;
                }
                $user_pl->user_id = $user->id;
                $user_pl->platform_id = $platform;
                $user_pl->save();
            }
        }
        $user->username = $this->username;
        $user->is_time_alert = (int)$this->is_time_alert;
        $user->time_alert = $this->time_alert;
        
        $user->days_of_week = $this->checkDaysOfWeek($this->days_of_week);
        
        $user->aaa_notifications = $this->aaa_notifications ? (int)$this->aaa_notifications : $user->aaa_notifications;
        $user->is_shares = $this->is_shares ? (int)$this->is_shares : $user->is_shares;
        $user->is_advertising = $this->is_advertising ? (int)$this->is_advertising : $user->is_advertising;

        return $user->save(false) ? true : false;
    }
    
    private function checkDaysOfWeek($array) {
        $result = [];
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $item) {
                if (in_array($item, User::DAYS_OF_WEEK)) {
                    $result[] = $item;
                }
            }
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            return [];
        }
    }

    private function uploadImage($base64_string, $user_id) {

        // Создаем директорию, для сохранения вложений
        $folder_path = self::DIR_NAME . "/User_{$user_id}/";
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777);
        }

        // Конвертируем пришедший файл из base64
        $data = base64_decode($base64_string);

        // Создание нового изображения из потока представленного строкой
        $source_img = imagecreatefromstring($data);
        if (!$source_img) {
            return false;
        }
        
        // Поворот изображения с заданным углом
        $rotated_img = imagerotate($source_img, 0, 0);

        // Задаем уникальное имя для загруженного файла
        $file_name = uniqid() . '.png';
        $file_path = $folder_path . $file_name;

        // Записываем изображение в файл
        $imageSave = imagejpeg($rotated_img, $file_path, 70);
        imagedestroy($source_img);

        return '/' . $file_path;
    }

}
