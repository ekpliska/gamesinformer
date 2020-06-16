<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Логи
 */
class m200616_142334_add_table__AppLogs extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%app_logs}}', [
            'id' => $this->primaryKey(),
            'value_1' => $this->string(255),
            'value_2' => $this->string(255),
            'value_3' => $this->string(255),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
        ], $table_options);
        
    }

    public function safeDown() {
        $this->dropTable('{{%app_logs}}');
    }

}
