<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Комнаты, чат
 */
class m200820_170937_add_table__chat extends Migration {
    
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%chat_room}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'is_public' => $this->tinyInteger()->defaultValue(1),
        ], $table_options);
        
        
        $this->createTable('{{%chat}}', [
            'id' => $this->primaryKey(),
            'chat_room_id' => $this->integer()->notNull(),
            'message' => $this->string(256)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_reading' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-chat-chat_room_id', 
            '{{%chat}}', 
            'chat_room_id', 
            '{{%chat_room}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-chat-user_id', 
            '{{%chat}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
        
        
    }

    public function safeDown() {
        $this->dropForeignKey('fk-chat-chat_room_id', '{{%chat}}');
        $this->dropForeignKey('fk-chat-user_id', '{{%chat}}');
        $this->dropTable('{{%chat}}');
        $this->dropTable('{{%chat_room}}');
    }

}
