<?php

use yii\db\Migration;

/**
 * Серии
 */
class m200606_151256_add_table__series extends Migration {

    public function safeUp() {
        $table_options = null;
        
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%series}}', [
            'id' => $this->primaryKey(),
            'series_name' => $this->integer()->notNull(),
            'description' => $this->string(),
            'image' => $this->string(),
            'enabled' => $this->tinyInteger()->defaultValue(0),
        ], $table_options);

        $this->createIndex('idx-series-id', '{{%series}}', 'id');

    }

    public function safeDown() {
        $this->dropIndex('idx-series-uid', '{{%series}}');
        $this->dropTable('{{%series}}');
    }

}
