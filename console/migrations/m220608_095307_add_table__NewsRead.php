<?php

use yii\db\Migration;

/**
 * Прочитанные новости
 */
class m220608_095307_add_table__NewsRead extends Migration
{
    public function safeUp() {
        $table_options = null;

        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%news_read}}', [
            'id' => $this->primaryKey(),
            'news_id' => $this->integer()->notNull(),
            'user_ids' => $this->text(5000),
        ], $table_options);

        $this->addForeignKey(
            'fk-news_read-news_id',
            '{{%news_read}}',
            'news_id',
            '{{%news}}',
            'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-news_read-news_id', '{{%news_read}}');
        $this->dropTable('{{%news_read}}');
    }

}
