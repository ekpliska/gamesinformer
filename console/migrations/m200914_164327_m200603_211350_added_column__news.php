<?php

use yii\db\Migration;

class m200914_164327_m200603_211350_added_column__news extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%news}}', 'is_block', $this->tinyInteger()->defaultValue(0));
    }
    
    public function safeDown() {
        $this->dropColumn('{{%news}}', 'is_block');
    }

}
