<?php

use yii\db\Migration;

/**
 * Поле "Дата выхода из приложения"
 */
class m220609_142307_add_column_logout_at__user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'logout_at', $this->dateTime()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'logout_at');
    }

}
