<?php

use yii\db\Migration;

/**
 * Новости
 */
class m200615_151845_add_table__news extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(20)->notNull(),
            'description' => $this->string(20)->notNull(),
            'pub_date' => $this->string(20)->notNull(),
            'image' => $this->string(20)->notNull(),
            'link' => $this->string(20)->notNull(),
            'rss_channel_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->createIndex('idx-news-id', '{{%news}}', 'id');
        
        $this->addForeignKey(
            'fk-news-rss_channel_id', 
            '{{%news}}', 
            'rss_channel_id', 
            '{{%rss_channel}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropForeignKey('fk-news-rss_channel_id', '{{%news}}');
        $this->dropIndex('idx-news-id', '{{%news}}');
        $this->dropTable('{{%news}}');
    }

}
