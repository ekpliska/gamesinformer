<?php

use yii\db\Migration;

/**
 * Поле "Дата входа в приложение"
 */
class m220617_124939_add_column_login_at__user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'login_at', $this->dateTime()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'login_at');
    }

}
