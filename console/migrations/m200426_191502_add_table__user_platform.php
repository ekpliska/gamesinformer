<?php

use yii\db\Migration;

/**
 * Платформы пользователя
 */
class m200426_191502_add_table__user_platform extends Migration {

    public function safeUp() {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_platform}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'platform_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->createIndex('idx-user_platform-id', '{{%user_platform}}', 'id');
        
        $this->addForeignKey(
                'fk-user_platform-user_id', 
                '{{%user_platform}}', 
                'user_id', 
                '{{%user}}', 
                'id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-user_platform-platform_id', 
                '{{%user_platform}}', 
                'platform_id', 
                '{{%platform}}', 
                'id', 
                'CASCADE',
                'CASCADE'
        );
        
    }

    public function safeDown() {
        $this->dropIndex('idx-user_platform-id', '{{%user_platform}}');
        $this->dropForeignKey('fk-user_platform-user_id', '{{%user_platform}}');
        $this->dropForeignKey('fk-user_platform-platform_id', '{{%user_platform}}');
        $this->dropTable('{{%user_platform}}');
    }

}
