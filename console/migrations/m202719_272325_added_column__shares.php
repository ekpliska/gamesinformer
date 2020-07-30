<?php

use yii\db\Migration;

/**
 * Class m202719_272325_added_column__shares
 */
class m202719_272325_added_column__shares extends Migration {

    public function safeUp() {
        $this->renameColumn('{{%shares}}', 'date', 'date_start');
        $this->addColumn('{{%shares}}', 'date_end', $this->dateTime());
    }

    public function safeDown() {
        $this->renameColumn('{{%shares}}', 'date_start', 'date');
        $this->dropColumn('{{%shares}}', 'date_end', $this->dateTime());
    }

}
