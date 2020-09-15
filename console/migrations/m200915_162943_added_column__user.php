<?php

use yii\db\Migration;

class m200915_162943_added_column__user extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%user}}', 'is_time_alert', $this->tinyInteger()->defaultValue(1));
        $this->addColumn('{{%user}}', 'days_of_week', $this->string(70));
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'is_time_alert');
        $this->dropColumn('{{%user}}', 'days_of_week');
    }

}
