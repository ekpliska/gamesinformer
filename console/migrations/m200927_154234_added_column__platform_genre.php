<?php

use yii\db\Migration;

class m200927_154234_added_column__platform_genre extends Migration {
    
    public function safeUp() {
        $this->addColumn('{{%platform}}', 'description', $this->text(1000));
        $this->addColumn('{{%platform}}', 'cover', $this->string(255));
        $this->addColumn('{{%platform}}', 'youtube', $this->string(255));
        $this->addColumn('{{%platform}}', 'is_used_filter', $this->tinyInteger()->defaultValue(1));
        $this->addColumn('{{%platform}}', 'is_preview_youtube', $this->tinyInteger()->defaultValue(0));

        $this->addColumn('{{%genre}}', 'description', $this->text(1000));
        $this->addColumn('{{%genre}}', 'cover', $this->string(255));
        $this->addColumn('{{%genre}}', 'youtube', $this->string(255));
        $this->addColumn('{{%genre}}', 'is_used_filter', $this->tinyInteger()->defaultValue(1));
        $this->addColumn('{{%genre}}', 'is_preview_youtube', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown() {
        $this->dropColumn('{{%platform}}', 'description');
        $this->dropColumn('{{%platform}}', 'cover');
        $this->dropColumn('{{%platform}}', 'youtube');
        $this->dropColumn('{{%platform}}', 'is_used_filter');
        $this->dropColumn('{{%platform}}', 'is_preview_youtube');

        $this->dropColumn('{{%genre}}', 'description');
        $this->dropColumn('{{%genre}}', 'cover');
        $this->dropColumn('{{%genre}}', 'youtube');
        $this->dropColumn('{{%genre}}', 'is_used_filter');
        $this->dropColumn('{{%genre}}', 'is_preview_youtube');
    }

}
