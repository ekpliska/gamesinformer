<?php

use yii\db\Migration;

/**
 * Реклама
 */
class m200704_114226_add_table__advertising extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%advertising}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->string(255),
            'preview' => $this->string(255),
            'youtube' => $this->string(255),
            'is_preview_youtube' => $this->tinyInteger()->defaultValue(0),
        ], $table_options);

    }

    public function safeDown() {
        $this->dropTable('{{%advertising}}');
    }

}
