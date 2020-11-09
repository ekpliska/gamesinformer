<?php

use yii\db\Migration;

/**
 * Game likes
 */
class m201109_165629_add_table__game_likes extends Migration {
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game_likes}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->addForeignKey(
            'fk-game_likes-user_id', 
            '{{%game_likes}}', 
            'user_id', 
            '{{%user}}', 
            'id', 
            'CASCADE', 
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-game_likes-game_id', 
            '{{%game_likes}}', 
            'game_id', 
            '{{%game}}', 
            'id', 
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropForeignKey('fk-game_likes-game_id', '{{%game_likes}}');
        $this->dropForeignKey('fk-game_likes-user_id', '{{%game_likes}}');
        $this->dropTable('{{%game_likes}}');
    }
}
