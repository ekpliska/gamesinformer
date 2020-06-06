<?php

use yii\db\Migration;

/**
 * Серии игр
 */
class m200606_152613_add_table__game_series extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game_series}}', [
            'id' => $this->primaryKey(),
            'series_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-game_series-game_id', 
            '{{%game_series}}', 
            'game_id', 
            '{{%game}}', 
            'id', 'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            'fk-game_series-series_id', 
            '{{%game_series}}', 
            'series_id', 
            '{{%series}}', 
            'id', 'CASCADE', 'CASCADE'
        );
    }

    public function safeDown() {
    }

}
