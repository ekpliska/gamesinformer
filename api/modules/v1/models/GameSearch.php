<?php

namespace api\modules\v1\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use api\modules\v1\models\Game;
use common\models\Platform;
use common\models\Genre;
use api\modules\v1\models\Series;

class GameSearch extends Game {

    public $platforms;
    public $geners;

    public function rules() {
        return [
            [[
            'title', 'series',
            'platforms', 'geners'
                ], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }

    public function search($params) {

        $platforms_ids = [];
        $genres_ids = [];
        $series = [];

        $query = Game::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->where(['published' => true]);

        if (isset($params['title'])) {
            $query->andFilterWhere(['like', 'title', $params['title']]);
        }
        
        // Фильтр по сериям
        if (isset($params['series'])) {
            $series_ids = $this->getSeriesId(Html::encode($params['series']));
            $query->joinWith(['seriesGame' => function($q) use ($series_ids) {
                $q->where(['in', 'series_id', $series_ids]);
            }]);
        }

        // Фильтр по жанрам
        if (isset($params['genres'])) {
            $genres_ids = $this->getGenresId(Html::encode($params['genres']));
            $query->joinWith(['gameGenres' => function($q) use ($genres_ids) {
                $q->where(['in', 'genre_id', $genres_ids]);
            }]);
        }

        // Фильтр по платформам
        if (isset($params['platforms'])) {
            $platforms_ids = $this->getPlatformsId(Html::encode($params['platforms']));
            $query->joinWith(['gamePlatformReleases' => function($q) use ($platforms_ids) {
                $q->where(['in', 'platform_id', $platforms_ids]);
                // Если в фильтре используется одна платформа сортируем список публикаций по дате редиза игры
                if (count($platforms_ids) == 1) {
                    $q->orderBy(['date_platform_release' => SORT_DESC]);
                }
            }]);
            // Если в фильтре используется более одной платформы сортируем список публикаций по дате публикации
            if (count($platforms_ids) > 1) {
                $query->orderBy(['publish_at' => SORT_DESC]);
            }
        } else {
            $query->orderBy(['publish_at' => SORT_DESC]);
        }


        return $dataProvider;
    }

    private function getPlatformsId($params) {
        $platforms = [];
        $platforms_ids = [];
        $platforms_names = [];
        if (count($params) > 0) {
            $platforms_names = explode(',', $params);
            $platforms = Platform::find()->where(['IN', 'name_platform', $platforms_names])->asArray()->all();
            $platforms_ids = ArrayHelper::getColumn($platforms, 'id');
        }
        return $platforms_ids;
    }

    private function getGenresId($params) {
        $genres = [];
        $genres_ids = [];
        $genres_names = [];
        if (count($params) > 0) {
            $genres_names = explode(',', $params);
            $genres = Genre::find()->where(['IN', 'name_genre', $genres_names])->asArray()->all();
            $genres_ids = ArrayHelper::getColumn($genres, 'id');
        }
        return $genres_ids;
    }
    
    private function getSeriesId($params) {
        $series = [];
        $series_ids = [];
        $series_names = [];
        if (count($params) > 0) {
            $series_names = explode(',', $params);
            $series = Series::find()->where(['IN', 'series_name', $series_names])->asArray()->all();
            $series_ids = ArrayHelper::getColumn($series, 'id');
        }
        return $series_ids;
    }

}
