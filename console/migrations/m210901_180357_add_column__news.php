<?php

use yii\db\Migration;

/**
 * Class m210901_180357_add_column__news
 */
class m210901_180357_add_column__news extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'number_views', $this->integer()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'number_views');
    }

}
