<?php

use yii\db\Migration;

/**
 * Избранные серии
 */
class m200915_182707_add_table__favorite_series extends Migration {
    
    public function safeUp() {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%favorite_series}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'series_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->createIndex('idx-favorite_series-user_uid', '{{%favorite_series}}', 'user_uid');
        $this->createIndex('idx-favorite_series-series_id', '{{%favorite_series}}', 'series_id');

        $this->addForeignKey(
            'fk-favorite_series-user_uid', 
            '{{%favorite_series}}', 
            'user_uid', 
            '{{%user}}', 
            'id', 
            'CASCADE', 
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-favorite_series-game_id', 
            '{{%favorite_series}}', 
            'series_id', 
            '{{%series}}', 
            'id', 
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropIndex('idx-favorite_series-user_uid', '{{%favorite_series}}');
        $this->dropIndex('idx-favorite_series-series_id', '{{%favorite_series}}');
        $this->dropForeignKey('fk-favorite_series-user_uid', '{{%favorite_series}}');
        $this->dropForeignKey('fk-favorite_series-series_id', '{{%favorite_series}}');
        $this->dropTable('{{%favorite_series}}');
    }
}
