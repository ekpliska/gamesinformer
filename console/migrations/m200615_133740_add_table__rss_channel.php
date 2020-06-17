<?php

use yii\db\Migration;

/**
 * RSS
 */
class m200615_133740_add_table__rss_channel extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%rss_channel}}', [
            'id' => $this->primaryKey(),
            'rss_channel_name' => $this->string(70)->notNull(),
            'rss_channel_url' => $this->string(255)->notNull(),
            'title_tag' => $this->string(20)->notNull(),
            'description_tag' => $this->string(20)->notNull(),
            'pub_date_tag' => $this->string(20)->notNull(),
            'image_tag' => $this->string(20),
            'link_tag' => $this->string(20)->notNull(),
        ], $table_options);

        $this->createIndex('idx-rss_channel-id', '{{%rss_channel}}', 'id');

        $this->batchInsert('{{%rss_channel}}', ['rss_channel_name', 'rss_channel_url', 'title_tag', 'description_tag', 'pub_date_tag', 'image_tag', 'link_tag'], [
            ['MMO13', 'https://mmo13.ru/rss/rss.xml', 'title', 'description', 'pubDate', '', 'link'],
            ['StopGame', 'https://rss.stopgame.ru/rss_news.xml', 'title', 'description', 'pubDate', 'image', 'link'],
            ['GOHA', 'https://www.goha.ru/rss/news', 'title', 'description', 'pubDate', '', 'link'],
            ['IGN Russia', 'https://ru.ign.com/feed.xml', 'title', 'description', 'pubDate', '', 'link'],
            ['KaNoBu', 'https://kanobu.ru/rss/kanobuvosti_itunes.xml', 'title', 'description', 'pubDate', '', 'link'],
            ['PlayGround', 'https://www.playground.ru/rss/news.xml', 'title', 'description', 'pubDate', 'enclosure', 'link'],
        ]);
    }

    public function safeDown() {
        $this->dropIndex('idx-rss_channel-id', '{{%rss_channel}}');
        $this->dropTable('{{%rss_channel}}');
    }

}
