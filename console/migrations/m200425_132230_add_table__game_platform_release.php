<?php

use yii\db\Migration;

/**
 * Релизы на платформах
 */
class m200425_132230_add_table__game_platform_release extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%game_platform_release}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'platform_id' => $this->integer()->notNull(),
            'date_platform_release' => $this->dateTime()->notNull()
        ], $tableOptions);

        $this->createIndex('ind-game_platform_release__id', '{{%game_platform_release}}', 'id');
        
        $this->addForeignKey(
                'fk-game_platform_release-game_id', 
                '{{%game_platform_release}}', 
                'game_id', 
                '{{%game}}', 
                'id', 
                'CASCADE',
                'CASCADE'
        );
        
        $this->addForeignKey(
                'fk-game_platform_release-pltaform_id', 
                '{{%game_platform_release}}', 
                'platform_id', 
                '{{%platform}}', 
                'id', 
                'CASCADE',
                'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropIndex('ind-game_platform_release__id', '{{%game_platform_release}}');
        $this->dropForeignKey('fk-game_platform_release-game_id', '{{%game_platform_release}}');
        $this->dropForeignKey('fk-game_platform_release-pltaform_id', '{{%game_platform_release}}');
        $this->dropTable('{{%game_platform_release}}');
    }

}
