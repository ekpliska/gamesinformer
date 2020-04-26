<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Пользователи
 */
class m200426_190728_add_table__user extends Migration {

    public function safeUp() {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string(70)->notNull()->unique(),
            'token' => $this->string(32)->notNull()->unique(),
            'photo' => $this->string(255),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'status' => $this->smallInteger()->notNull()->defaultValue(true),
        ], $table_options);
        $this->createIndex('idx-user-id', '{{%user}}', 'id');
    }

    public function safeDown() {
        $this->dropIndex('idx-user-id', '{{%user}}');
        $this->dropTable('{{%user}}');
    }

}
