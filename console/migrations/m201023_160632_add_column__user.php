<?php

use yii\db\Migration;

class m201023_160632_add_column__user extends Migration {

    public function safeUp() {
        $this->addColumn('{{%user}}', 'is_favorite_series', $this->tinyInteger()->defaultValue(1));
    }

    public function safeDown() {
        $this->dropColumn('{{%user}}', 'is_favorite_series');
    }

}
