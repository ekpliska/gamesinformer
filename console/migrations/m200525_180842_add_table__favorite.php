<?php

use yii\db\Migration;

/**
 * Избранное
 */
class m200525_180842_add_table__favorite extends Migration {

    public function safeUp() {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'user_uid' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
                ], $table_options);

        $this->createIndex('idx-favorite-user_uid', '{{%favorite}}', 'user_uid');
        $this->createIndex('idx-favorite-game_id', '{{%favorite}}', 'game_id');

        $this->addForeignKey('fk-favorite-user_uid', '{{%favorite}}', 'user_uid', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey(
            'fk-favorite-game_id', 
            '{{%favorite}}', 
            'game_id', 
            '{{%game}}', 
            'id', 
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown() {
        $this->dropIndex('idx-favorite-user_uid', '{{%favorite}}');
        $this->dropIndex('idx-favorite-game_id', '{{%favorite}}');
        $this->dropForeignKey('fk-favorite-user_uid', '{{%favorite}}');
        $this->dropForeignKey('fk-favorite-game_id', '{{%favorite}}');
        $this->dropTable('{{%favorite}}');
    }

}
