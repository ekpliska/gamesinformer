<?php

use yii\db\Migration;

class m200917_185249_add_column__token_push_mobile extends Migration {

    public function safeUp() {
        $this->addColumn('{{%token_push_mobile}}', 'user_uid', $this->integer());
    }

    public function safeDown() {
        $this->dropColumn('{{%token_push_mobile}}', 'user_uid');
    }

}
