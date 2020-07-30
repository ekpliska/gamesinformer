<?php

use yii\db\Migration;

/**
 * Class m202719_272325_added_column__shares
 */
class m202719_272325_added_column__shares extends Migration {

    public function safeUp() {
        $this->execute("ALTER TABLE `shares` CHANGE `date` `date_start` DATETIME NOT NULL");
        $this->addColumn('{{%shares}}', 'date_end', $this->dateTime());
    }

    public function safeDown() {
        $this->execute("ALTER TABLE `shares` CHANGE `date_start` `date` DATETIME NOT NULL");
        $this->dropColumn('{{%shares}}', 'date_end', $this->dateTime());
    }

}
