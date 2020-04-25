<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Игры
 */
class m200425_125153_add_table__game extends Migration {
 
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(170)->notNull(),
            'description' => $this->text(3000),
            'series' => $this->string(255),
            'release_date' => $this->dateTime()->notNull(),
            'publish_at' => $this->dateTime()->notNull(),
            'published' => $this->boolean()->defaultValue(false),
            'cover' => $this->string(255)->notNull(),
            'website' => $this->string(255)->notNull(),
            'youtube' => $this->string(255)->notNull(),
            'youtube_btnlink' => $this->string(255)->notNull(),
            'twitch' => $this->string(255),
            'created_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
            'updated_at' => $this->dateTime()->defaultValue(new Expression("NOW()")),
        ], $tableOptions);

        $this->createIndex('ind-game__id', '{{%game}}', 'id');
        
    }

    public function safeDown() {
        $this->dropIndex('ind-game__id', '{{%game}}');
        $this->dropTable('{{%game}}');
    }

}
