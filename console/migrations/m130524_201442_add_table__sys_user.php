<?php

use yii\db\Migration;

/*
 * Пользователи системы
 */
class m130524_201442_add_table__sys_user extends Migration {
    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sys_user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        
        $this->createIndex('ind-sys_user__id', '{{%sys_user}}', 'id');
    }

    public function down() {
        $this->dropIndex('ind-sys_user__id', '{{%sys_user}}');
        $this->dropTable('{{%sys_user}}');
    }
}
