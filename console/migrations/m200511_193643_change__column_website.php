<?php

use yii\db\Migration;

class m200511_193643_change__column_website extends Migration {

    public function safeUp() {
        $this->alterColumn('{{%game}}', 'website', $this->string(255));
    }

    public function safeDown() {
        $this->alterColumn('{{%game}}', 'website', $this->string(255)->notNull());
    }

}
