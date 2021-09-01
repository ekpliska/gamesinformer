<?php

use yii\db\Migration;

/**
 * Class m210901_170350_add_column__shares
 */
class m210901_170350_add_column__shares extends Migration
{
    public function safeUp() {
        $this->addColumn('{{%shares}}', 'is_published', $this->tinyInteger()->defaultValue(1));
    }

    public function safeDown() {
        $this->dropColumn('{{%shares}}', 'is_published');
    }
}
