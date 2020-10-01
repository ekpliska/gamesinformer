<?php

use yii\db\Migration;

class m200930_174942_add_column__game extends Migration {

    public function safeUp() {
        $this->addColumn('{{%game}}', 'only_year', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%game}}', 'only_year');
    }

}
