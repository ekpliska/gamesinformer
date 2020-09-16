<?php

use yii\db\Migration;

class m200916_164557_add_column__user extends Migration {

    public function safeUp() {
        $this->alterColumn('{{%user}}', 'is_time_alert', $this->tinyInteger()->defaultValue(0));
        $this->alterColumn('{{%user}}', 'aaa_notifications', $this->tinyInteger()->defaultValue(1));
        
        $this->addColumn('{{%user}}', 'is_advertising', $this->tinyInteger()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_shares', $this->tinyInteger()->defaultValue(1));
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'is_advertising');
        $this->dropColumn('{{%user}}', 'is_shares');
    }
    
}
