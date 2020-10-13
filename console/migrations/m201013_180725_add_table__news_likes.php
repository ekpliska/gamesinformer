<?php

use yii\db\Migration;

/**
 * Лайки для новостей
 */

class m201013_180725_add_table__news_likes extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_likes}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->addForeignKey(
            'fk-news_likes-user_uid', 
            '{{%news_likes}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-news_likes-news_uid', 
            '{{%news_likes}}', 
            'news_id', 
            '{{%news}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropForeignKey('fk-news_likes-user_uid', '{{%news_likes}}');
        $this->dropForeignKey('fk-news_likes-news_uid', '{{%news_likes}}');
        $this->dropTable('{{%news_likes}}');
    }

}
