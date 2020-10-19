<?php

use yii\db\Migration;

class m201014_180959_add_column__tags__news extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%news}}', 'tags', $this->string(255));
    }
    
    public function safeDown() {
        $this->dropColumn('{{%news}}', 'tags');
    }
}
