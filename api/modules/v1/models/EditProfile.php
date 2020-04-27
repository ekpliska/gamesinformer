<?php

namespace api\modules\v1\models;
use Yii;
use yii\base\Model;
use api\modules\v1\models\User;

/**
 * Редактирование профиля
 */
class EditProfile extends Model {
    
    const DIR_NAME = 'uploads/user_photo';
    
    public $username;
    public $email;
    public $photo;
    
    private $_user;


    public function __construct(User $user, $config = []) {
        $this->_user = $user;
        parent::__construct($config);
    }

        public function rules() {
        return [
            ['email', 'required'],
            ['username', 'string', 'max' => 70],
            ['email', 'email'],
            ['photo', 'safe'],
        ];
    }
    
    public function save() {
        if (!$this->validate()) {
            return false;
        }
        
        return $this->uploadImage($this->photo, $this->_user->id);
        
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
        // Поворот изображения с заданным углом
        $rotated_img = imagerotate($source_img, 0, 0);

        // Задаем уникальное имя для загруженного файла
        $file_name = uniqid(). '.png';
        $file_path = $folder_path . $file_name;

        // Записываем в БД пути загруженных вложений
        $user = $this->_user;
        $user->photo = $file_path;
        $user->save(false);
            
        // Записываем изображение в файл
        $imageSave = imagejpeg($rotated_img, $file_path, 70);
        imagedestroy($source_img);
        
        return true;
        
    }
    
}
