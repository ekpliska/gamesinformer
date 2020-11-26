<?php

use yii\db\Migration;

class m201008_190522_add_column__shares extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%shares}}', 'game_list', $this->string(255));
    }

    public function safeDown() {
        $this->dropColumn('{{%shares}}', 'game_list');
    }

}
