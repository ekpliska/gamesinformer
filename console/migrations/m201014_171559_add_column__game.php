<?php

use yii\db\Migration;

class m201014_171559_add_column__game extends Migration {

    public function safeUp() {
        $this->addColumn('{{%game}}', 'tags', $this->string(255));
        $this->execute('UPDATE game SET tags = CONCAT(`title`,";");');
    }
    
    public function safeDown() {
        $this->dropColumn('{{%game}}', 'tags');
    }
}
