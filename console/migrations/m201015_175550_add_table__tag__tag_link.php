<?php

use yii\db\Migration;

/**
 * Теги, связи
 */
class m201015_175550_add_table__tag__tag_link extends Migration {

    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
        ], $table_options);
        
        $this->createTable('{{%tag_link}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(10)->notNull(),
            'type_uid' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $table_options);
        
        $this->addForeignKey(
            'fk-tag_link-tag_id', 
            '{{%tag_link}}', 
            'tag_id', 
            '{{%tag}}', 
            'id', 'CASCADE', 'CASCADE'
        );
        
    }

    public function safeDown() {
        $this->dropForeignKey('fk-tag_link-tag_id', '{{%tag_link}}');
        $this->dropTable('{{%tag_link}}');
        $this->dropTable('{{%tag}}');
    }

}
