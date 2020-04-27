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
            'name_platform' => $this->string(70)->notNull()->unique(),
            'logo_path' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createIndex('ind-platform__id', '{{%platform}}', 'id');

        $this->batchInsert('{{%platform}}', ['name_platform'], [
            ['2DS/3DS', '/images/platforms_logo/23ds.png'],
            ['Android', '/images/platforms_logo/android.png'],
            ['PC', '/images/platforms_logo/pc.png'],
            ['PC VR', '/images/platforms_logo/pcvr.png'],
            ['PS VR', '/images/platforms_logo/psvr.png'],
            ['PS Vita', '/images/platforms_logo/psvita.png'],
            ['PlayStation 4', '/images/platforms_logo/ps4.png'],
            ['Stadia', '/images/platforms_logo/stadia.png'],
            ['Switch', '/images/platforms_logo/switch.png'],
            ['Xbox One', '/images/platforms_logo/xbox1.png'],
            ['iOS', '/images/platforms_logo/ios.png']
        ]);
    }

    public function safeDown() {
        $this->dropIndex('ind-platform__id', '{{%platform}}');
        $this->dropTable('{{%platform}}');
    }

}
