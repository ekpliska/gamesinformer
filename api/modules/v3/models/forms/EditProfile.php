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
            [['platforms', 'days_of_week'], 'safe'],
            [['aaa_notifications', 'is_time_alert'], 'integer'],
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
        $user->is_time_alert = $this->is_time_alert;
        $user->time_alert = $this->time_alert;
        
        var_dump($this->checkDaysOfWeek($this->days_of_week)); die();
        $user->days_of_week = $this->checkDaysOfWeek($this->days_of_week);
            
        $user->aaa_notifications = $this->aaa_notifications;

        return $user->save(false) ? true : false;
    }
    
    private function checkDaysOfWeek($array) {
        $result = [];
        if (is_array($array) && count($array) > 0) {
            for ($i = 0; $i <= count($array); $i++) {
                if (in_array($array[$i], User::DAYS_OF_WEEK)) {
                    $result[] = $array[$i];
                }
            }
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            return null;
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
