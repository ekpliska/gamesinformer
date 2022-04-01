<?php

use yii\db\Migration;

class m220329_201121_add_columns__rss_channel extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%rss_channel}}', 'root_tag', $this->string(70));
        $this->addColumn('{{%rss_channel}}', 'item_tag', $this->string(70)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%rss_channel}}');
        $this->dropColumn('{{%rss_channel}}');
    }
}
