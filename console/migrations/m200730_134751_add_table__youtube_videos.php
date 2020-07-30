<?php

use yii\db\Migration;

/**
 * Class m200730_134751_add_table__youtube_videos
 */
class m200730_134751_add_table__youtube_videos extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%youtube_videos}}', [
            'id' => $this->primaryKey(),
            'youtube_rss_id' => $this->integer()->notNull(),
            'title' => $this->string(255),
            'description' => $this->text(1000),
            'link' => $this->string(255),
            'thumbnail' => $this->string(255),
            'community' => $this->text(1000),
            'published' => $this->dateTime(),
            'updated' => $this->dateTime(),
        ], $table_options);
        
        $this->createIndex('ind-youtube_videos__id', '{{%youtube_videos}}', 'id');

        $this->addForeignKey(
            'fk-youtube_videos-youtube_rss_id', 
            '{{%youtube_videos}}', 
            'youtube_rss_id', 
            '{{%youtube_rss}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );

    }

    public function safeDown() {
        $this->dropForeignKey('fk-youtube_videos-youtube_rss_id', '{{%youtube_videos}}');
        $this->dropIndex('ind-youtube_rss__id', '{{%youtube_videos}}');
        $this->dropTable('{{%youtube_rss}}');
    }

}
