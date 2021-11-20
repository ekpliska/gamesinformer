<?php

namespace common\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\components\notifications\Notifications;

/**
 * This is the model class for table "series".
 *
 * @property int $id
 * @property string $series_name
 * @property string|null $description
 * @property string|null $image
 * @property int|null $enabled
 *
 * @property GameSeries[] $gameSeries
 */
class Series extends ActiveRecord {

    public $image_file;
    public $game_ids;
    
    private $_user;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->_user = $this->checkAuthUser();
    }
    
    public static function tableName() {
        return 'series';
    }

    public function rules() {
        return [
            [['series_name'], 'required'],
            [['series_name'], 'string', 'max' => 70],
            [['enabled'], 'integer'],
            [['description'], 'string', 'max' => 1000],
            [['image'], 'string', 'max' => 255],
            [['image_file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            ['game_ids', 'safe'],
        ];
    }
    
    public function beforeSave($insert) {

        $current_image = $this->image;

        $file = UploadedFile::getInstance($this, 'image_file');
        
        if ($file) {
            $this->image = $file;
            $dir = Yii::getAlias('@api/web');
            $file_name = '/images/series/' . time() . '.' . $this->image->extension;
            $this->image->saveAs($dir . $file_name);
            $this->image = $file_name;
            @unlink(Yii::getAlias(Yii::getAlias('@api/web') . $current_image));
        }
        
        if (!$insert) {
            if (count($this->gameSeries) > 0) {
                $series_ids = ArrayHelper::getColumn($this->gameSeries, 'game_id');
                $new_series_list = $this->game_ids;
                $new_series_ids = [];
                
                if (count($series_ids) > count($new_series_list)) {
                    $new_series_ids = array_diff($series_ids, $new_series_list);
                } else {
                    $new_series_ids = array_diff($new_series_list, $series_ids);
                }
                
                if (count($new_series_list) > 0) {
                    $game = count($new_series_ids) !== 1 ? null : Game::find()->where(['AND', ['id' => current($new_series_ids)], ['published' => 1]]);
                    $series = Series::findOne(['id' => $this->id]);
                    $notification_series = new Notifications(Notifications::SERIES_TYPE, $game, $series);
                    $notification_series->createNotification();
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'series_name' => 'Серия',
            'description' => 'Описание',
            'image' => 'Изображения',
            'enabled' => 'Показывать в мобильном приложении',
            'image_file' => 'Изображения',
            'game_ids' => 'Список игр',
        ];
    }

    public function getGameSeries() {
        return $this->hasMany(GameSeries::className(), ['series_id' => 'id']);
    }
    
    public function isFavorite() {
        if (!$this->_user) {
            return false;
        }

        $favorite = FavoriteSeries::find()->andWhere(['AND', ['user_uid' => $this->_user->id], ['series_id' => $this->id]])->asArray()->one();
        if (!$favorite) {
            return false;
        }

        return true;
    }
    
    private function checkAuthUser() {
        $_headers = getallheaders();
        $headers = array_change_key_case($_headers);
        $auth_token = isset($headers['authorization']) ? $headers['authorization'] : null;
        if ($auth_token) {
            $token = trim(substr($auth_token, 6));
            $user = User::find()->where(['token' => $token])->one();
            return $user;
        }
        return false;
    }
    
}
