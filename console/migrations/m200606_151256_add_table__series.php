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
            'series_name' => $this->string(70)->notNull(),
            'description' => $this->text(1000),
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
