<?php

use yii\db\Migration;

class m200927_154234_added_column__platform_genre extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%platform}}', 'description', $this->text(1000));
        $this->addColumn('{{%platform}}', 'cover', $this->string(255));
        $this->addColumn('{{%platform}}', 'youtube', $this->string(255));

        $this->addColumn('{{%genre}}', 'description', $this->text(1000));
        $this->addColumn('{{%genre}}', 'cover', $this->string(255));
        $this->addColumn('{{%genre}}', 'youtube', $this->string(255));
    }

    public function safeDown() {
        $this->dropColumn('{{%platform}}', 'description');
        $this->dropColumn('{{%platform}}', 'cover');
        $this->dropColumn('{{%platform}}', 'youtube');

        $this->dropColumn('{{%genre}}', 'description');
        $this->dropColumn('{{%genre}}', 'cover');
        $this->dropColumn('{{%genre}}', 'youtube');
    }

}
