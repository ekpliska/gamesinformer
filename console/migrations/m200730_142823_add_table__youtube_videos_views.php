<?php

use yii\db\Migration;

/**
 * Class m200730_142823_add_table__youtube_videos_views
 */
class m200730_142823_add_table__youtube_videos_views extends Migration {
    
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%youtube_videos_views}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'youtube_videos_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-youtube_videos_views-user_id', 
            '{{%youtube_videos_views}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-youtube_videos_views-youtube_videos_id', 
            '{{%youtube_videos_views}}', 
            'youtube_videos_id', 
            '{{%youtube_videos}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {

    }

}
