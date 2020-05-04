<?php

use yii\db\Migration;

/**
 * Пуш уведомления
 */
class m200504_153227_add_table__token_push extends Migration {
    
    public function safeUp() {
        
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%token_push_mobile}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()->unique(),
            'enabled' => $this->tinyInteger()->defaultValue(1),
        ], $table_options);
        
        $this->createIndex('idx-token_push_mobile-user_uid', '{{%token_push_mobile}}', 'user_uid');
        $this->addForeignKey('fk-token_push_mobile-user_uid', 
                '{{%token_push_mobile}}', 
                'user_uid', 
                '{{%user}}', 
                'id', 
                'CASCADE', 
                'CASCADE');

    }
    public function safeDown() {
        $this->dropIndex('idx-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        $this->dropForeignKey('fk-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        $this->dropTable('{{%token_push_mobile}}');
    }

}
