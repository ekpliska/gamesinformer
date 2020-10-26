<?php

use yii\db\Migration;

class m201022_121445_add_column__user extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user}}', 'is_subscription', $this->tinyInteger()->defaultValue(0));
        $this->addColumn('{{%user}}', 'is_favorite_list', $this->tinyInteger()->defaultValue(1));
        
        $this->alterColumn('{{%user}}', 'aaa_notifications', $this->tinyInteger()->defaultValue(0));
        $this->alterColumn('{{%user}}', 'is_shares', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'is_subscription');
        $this->dropColumn('{{%user}}', 'is_favorite_list');
        
        $this->alterColumn('{{%user}}', 'aaa_notifications', $this->tinyInteger()->defaultValue(1));
        $this->alterColumn('{{%user}}', 'is_shares', $this->tinyInteger()->defaultValue(1));
    }

}
