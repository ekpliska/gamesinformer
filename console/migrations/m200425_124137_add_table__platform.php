<?php

use yii\db\Migration;

/**
 * Платформы
 */
class m200425_124137_add_table__platform extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%platform}}', [
            'id' => $this->primaryKey(),
            'name_platform' => $this->string()->notNull()->unique(),
                ], $tableOptions);

        $this->createIndex('ind-platform__id', '{{%platform}}', 'id');

        $this->batchInsert('{{%platform}}', ['name_platform'], [
            ['2DS/3DS'],
            ['Android'],
            ['PC'],
            ['PC VR'],
            ['PS VR'],
            ['PS Vita'],
            ['PlayStation 4'],
            ['Stadia'],
            ['Switch'],
            ['Xbox One'],
            ['iOS']
        ]);
    }

    public function safeDown() {
        $this->dropIndex('ind-platform__id', '{{%platform}}');
        $this->dropTable('{{%platform}}');
    }

}
