<?php

namespace frontend\models\searchFrom;
use yii\data\ActiveDataProvider;
use common\models\Platform;

class PlatformSearch extends Platform {
 
    public function rules() {
        return [
            [['name_platform'], 'string', 'max' => 70],
            [['logo_path'], 'string', 'max' => 255],
            [['isRelevant'], 'integer'],
        ];
    }

    public function search($params) {
        $query = Platform::find()->groupBy('id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->orderBy(['isRelevant' => SORT_DESC]);

        return $dataProvider;
    }
}
