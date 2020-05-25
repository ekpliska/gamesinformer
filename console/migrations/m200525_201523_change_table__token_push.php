<?php

use yii\db\Migration;

class m200525_201523_change_table__token_push extends Migration {

    public function safeUp() {
        $this->dropForeignKey('fk-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        $this->dropIndex('idx-token_push_mobile-user_uid', '{{%token_push_mobile}}');
        $this->dropColumn('{{%token_push_mobile}}', 'user_uid');
        $this->addColumn('{{%token_push_mobile}}', 'is_auth', $this->tinyInteger()->defaultValue(false));
    }

    public function safeDown() {
        $this->addColumn('{{%token_push_mobile}}', 'user_uid', $this->integer()->notNull());
        $this->dropColumn('{{%token_push_mobile}}', 'is_auth');
    }

}
