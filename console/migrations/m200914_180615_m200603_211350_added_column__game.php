<?php

use yii\db\Migration;

class m200914_180615_m200603_211350_added_column__game extends Migration {

    public function safeUp() {
        $this->addColumn('{{%game}}', 'is_aaa', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%game}}', 'is_aaa');
    }

}
