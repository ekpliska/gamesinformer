<?php

namespace frontend\models\searchFrom;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TagLink;

/**
 * TagSearch represents the model behind the search form of `common\models\TagLink`.
 */
class TagSearch extends TagLink {
 
    public $tag_name;
    public $game_id;
    
    public function rules() {
        return [
            [['id', 'type_uid'], 'integer'],
            [['tag_id', 'tag_name', 'game_id'], 'safe'],
        ];
    }

    public function scenarios() {
        return Model::scenarios();
    }
    
    public function search($params) {
        $query = TagLink::find()->where(['type' => TagLink::TYPE_LIST[502]]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 40,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->joinWith('tag')->orderBy(['tag.name' => SORT_ASC]);
        
        $query->joinWith(['tag' => function($q) {
            $q->andFilterWhere(['LIKE', 'tag.name', $this->tag_name]);
        }]);
        
        $query->andFilterWhere(['type_uid' => $this->game_id]);

        return $dataProvider;
    }
}
