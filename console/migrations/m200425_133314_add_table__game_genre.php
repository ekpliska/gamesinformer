<?php

use yii\db\Migration;

/**
 * Жанры
 */
class m200425_133314_add_table__game_genre extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game_genre}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'genre_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('ind-game_genre__id', '{{%game_genre}}', 'id');

        $this->addForeignKey(
                'fk-game_genre-game_id', 
                '{{%game_genre}}', 
                'game_id', 
                '{{%game}}', 
                'id', 
                'CASCADE', 
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-game_genre-genre_id', 
                '{{%game_genre}}', 
                'genre_id', 
                '{{%genre}}', 
                'id', 
                'CASCADE', 
                'CASCADE'
        );

    }

    public function safeDown() {
        $this->dropIndex('ind-game_genre__id', '{{%game_genre}}');
        $this->dropForeignKey('fk-game_genre-game_id', '{{%game_genre}}');
        $this->dropForeignKey('fk-game_genre-game_id', '{{%game_genre}}');
        $this->dropTable('{{%game_genre}}');
    }

}
