<?php

use yii\db\Migration;

class m200914_183203_m200603_211350_added_column__user extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user}}', 'time_alert', $this->time());
        $this->addColumn('{{%user}}', 'aaa_notifications', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'time_alert');
        $this->dropColumn('{{%user}}', 'aaa_notifications');
    }
    
}
