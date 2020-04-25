<?php

use yii\db\Migration;
use yii\db\Expression;

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
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->dateTime()->defaultValue(new Expression("NOW()"))
        ], $tableOptions);
        
        $this->createIndex('ind-sys_user__id', '{{%sys_user}}', 'id');
        
        $this->insert('{{%sys_user}}', [
            'username' => 'admin',
            'email' => 'inbox@gamenotificator.net',
            'password_hash' => '$2y$13$gFCzZXaf342gTBLxRD0zu.5ttnNdEsuWUUblmhQ4Es.yQz2JNPRlS'
        ]);
        
    }

    public function down() {
        $this->dropIndex('ind-sys_user__id', '{{%sys_user}}');
        $this->dropTable('{{%sys_user}}');
    }
}
