<?php

use yii\db\Migration;

/**
 * Class m200731_073522_added_columns__news
 */
class m200731_073522_added_columns__news extends Migration {

    public function safeUp() {
        $this->addColumn('{{%news}}', 'updated', $this->dateTime());
        $this->addColumn('{{%news}}', 'community', $this->text());
    }

    public function safeDown() {
        $this->dropColumn('{{%news}}', 'updated');
        $this->dropColumn('{{%news}}', 'community');
    }

}
