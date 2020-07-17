<?php

use yii\db\Migration;

/**
 * Скидки, акции, раздачи
 */
class m201007_211000_add_table__shares extends Migration {
    
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shares}}', [
            'id' => $this->primaryKey(),
            'type_share' => $this->integer()->notNull(),
            'description' => $this->text(1000),
            'cover' => $this->string(255),
            'link' => $this->string(255),
            'date' => $this->dateTime(),
        ], $table_options);
        
        $this->createIndex('ind-shares__id', '{{%shares}}', 'id');
        
    }

    public function safeDown() {
        $this->dropIndex('ind-shares__id', '{{%shares}}');
        $this->dropTable('{{%shares}}');
    }

}
