<?php

use yii\db\Migration;

class m211025_191042_add_column__is_prepare extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%game}}', 'is_prepare', $this->tinyInteger()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%game}}', 'is_prepare');
    }

}
