<?php

use yii\db\Migration;

class m200603_211350_added_column__platform__genre extends Migration {

    public function safeUp() {
        $this->addColumn('{{%platform}}', 'isRelevant', $this->tinyInteger()->defaultValue(0));
        $this->addColumn('{{%genre}}', 'isRelevant', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%platform}}', 'isRelevant');
        $this->dropColumn('{{%genre}}', 'isRelevant');
    }

}
