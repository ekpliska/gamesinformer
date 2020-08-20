<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Комментарии к играм
 */
class m200820_171845_add_table__comments extends Migration {

    public function safeUp() {
        
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'message' => $this->string(256)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
        ], $table_options);

        $this->addForeignKey(
            'fk-comments-game_id', 
            '{{%comments}}', 
            'game_id', 
            '{{%game}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-comments-user_id', 
            '{{%comments}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropForeignKey('fk-comments-game_id', '{{%comments}}');
        $this->dropForeignKey('fk-comments-user_id', '{{%comments}}');
        $this->dropTable('{{%comments}}');
    }

}
