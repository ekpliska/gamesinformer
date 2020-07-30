<?php

use yii\db\Migration;

/**
 * Class m200730_134456_add_table__youtube_rss
 */
class m200730_134456_add_table__youtube_rss extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%youtube_rss}}', [
            'id' => $this->primaryKey(),
            'channel_id' => $this->string(100)->notNull(),
            'name' => $this->string(100),
            'url' => $this->string(255),
        ], $table_options);
        
        $this->createIndex('ind-youtube_rss__id', '{{%youtube_rss}}', 'id');
    }

    public function safeDown() {
        $this->dropIndex('ind-youtube_rss__id', '{{%youtube_rss}}');
        $this->dropTable('{{%youtube_rss}}');
    }

}
