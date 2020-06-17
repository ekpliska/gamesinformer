<?php

use yii\db\Migration;

/**
 * Просмотры новостей
 */
class m200616_192920_add_table__news_views extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_views}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'news_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-news_views-user_id', 
            '{{%news_views}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-news_views-news_id', 
            '{{%news_views}}', 
            'news_id', 
            '{{%news}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropForeignKey('fk-news_views-user_id', '{{%news_views}}');
        $this->dropForeignKey('fk-news_views-news_id', '{{%news_views}}');
        $this->dropTable('{{%news_views}}');
    }

}
