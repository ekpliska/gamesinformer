<?php

use yii\db\Migration;

/**
 * Топ игр для каждой платформы и жанра
 */
class m200927_154237_add_table__top_games extends Migration {
    
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%top_games}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(10)->notNull(),
            'type_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-top_games-game_id', 
            '{{%top_games}}', 
            'game_id', 
            '{{%game}}', 
            'id', 'CASCADE', 'CASCADE'
        );

    }

    public function safeDown() {
        $this->dropForeignKey('fk-top_games-game_id', '{{%top_games}}');
        $this->dropTable('{{%top_games}}');
    }

}
