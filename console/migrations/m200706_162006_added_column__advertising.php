<?php

use yii\db\Migration;

/**
 * Class m200706_162006_added_column__advertising
 */
class m200706_162006_added_column__advertising extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%advertising}}', 'btn_title', $this->string(150));
        $this->addColumn('{{%advertising}}', 'link', $this->string(255));
    }

    public function safeDown() {
        $this->dropColumn('{{%advertising}}', 'btn_title');
        $this->dropColumn('{{%advertising}}', 'link');
    }

}
