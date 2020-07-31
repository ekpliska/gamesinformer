<?php

use yii\db\Migration;
use common\models\RssChannel;

/**
 * Class m200730_172103_added_columns__rss_channel
 */
class m200730_172103_added_columns__rss_channel extends Migration {

    public function safeUp() {
        $this->addColumn('{{%rss_channel}}', 'type', $this->string(10)->notNull());
        $this->addColumn('{{%rss_channel}}', 'channel_id', $this->string(100));
        $this->addColumn('{{%rss_channel}}', 'site_url', $this->string(255));
        $this->alterColumn('{{%rss_channel}}', 'title_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'description_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'pub_date_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'link_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'rss_channel_url', $this->string(255));

        $this->update('{{%rss_channel}}', ['type' => RssChannel::TYPE_NEWS]);

    }

    public function safeDown() {
        $this->dropColumn('{{%rss_channel}}', 'type');
        $this->dropColumn('{{%rss_channel}}', 'channel_id');
        $this->alterColumn('{{%rss_channel}}', 'title_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'description_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'pub_date_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'link_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'rss_channel_url', $this->string(255)->notNull());
    }

}
