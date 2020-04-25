<?php

use yii\db\Migration;

/**
 * Жанры
 */
class m200425_123755_add_table__genre extends Migration {

    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%genre}}', [
            'id' => $this->primaryKey(),
            'name_genre' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createIndex('ind-genre__id', '{{%genre}}', 'id');
        
        $this->batchInsert('{{%genre}}', ['name_genre'], [
            ['Аркада'],
            ['3D-экшн'],
            ['Шутер'],
            ['Стратегия'],
            ['Пошаговая стратегия'],
            ['РПГ'],
            ['Квест'],
            ['Головоломка'],
            ['Файтинг'],
            ['Симулятор'],
            ['ММО'],
            ['Визуальная новелла'],
            ['Выживание в кошмаре'],
            ['Платформер'],
            ['Гонки'],
            ['ММОРПГ'],
            ['Казуальная игра'],
            ['ККИ'],
            ['Защита башен'],
            ['ЯРПГ'],
            ['Песочница']
        ]);
    }
    
    public function safeDown() {
        $this->dropIndex('ind-genre__id', '{{%genre}}');
        $this->dropTable('{{%genre}}');
    }

}
