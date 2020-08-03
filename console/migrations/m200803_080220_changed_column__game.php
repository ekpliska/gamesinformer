<?php

use yii\db\Migration;

/**
 * Class m200803_080220_changed_column__game
 */
class m200803_080220_changed_column__game extends Migration {

    public function safeUp() {
        $this->alterColumn('{{%game}}', 'cover', $this->string(255));
    }

    public function safeDown() {
        $this->alterColumn('{{%game}}', 'cover', $this->string(255)->notNull());
    }

}
