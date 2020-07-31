<?php

use yii\db\Migration;
use common\models\RssChannel;

/**
 * Class m200730_172103_added_columns__rss_channel
 */
class m200730_172103_added_columns__rss_channel extends Migration {

    public function safeUp() {
        $this->addColumn('{{%rss_channel}}', 'type', $this->string(10)->notNull());
        $this->addColumn('{{%rss_channel}}', 'channel_id', $this->string(100));
        $this->addColumn('{{%rss_channel}}', 'site_url', $this->string(255));
        $this->alterColumn('{{%rss_channel}}', 'title_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'description_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'pub_date_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'link_tag', $this->string(20));
        $this->alterColumn('{{%rss_channel}}', 'rss_channel_url', $this->string(255));

        $this->update('{{%rss_channel}}', ['type' => RssChannel::TYPE_NEWS]);

        $this->batchInsert('{{%rss_channel}}', ['rss_channel_name', 'rss_channel_url', 'channel_id', 'site_url', 'type'], [
            [
                'PlayGround.ru',
                'https://www.youtube.com/feeds/videos.xml?channel_id=UCN1U8E6ClQVC7y6N9HpMm9Q',
                'UCN1U8E6ClQVC7y6N9HpMm9Q',
                'https://www.youtube.com/channel/UCN1U8E6ClQVC7y6N9HpMm9Q',
                RssChannel::TYPE_YOUTUBE,
            ],
            [
                'Игромания',
                'http://www.youtube.com/feeds/videos.xml?channel_id=UC_Q1vhf7wcR_zGlc5ahAg0A',
                'UC_Q1vhf7wcR_zGlc5ahAg0A',
                'https://www.youtube.com/channel/UC_Q1vhf7wcR_zGlc5ahAg0A',
                RssChannel::TYPE_YOUTUBE,
            ],
            [
                'Навигатор игрового мира',
                'http://www.youtube.com/feeds/videos.xml?channel_id=UCfDPpLHN-7_6cctaU8CgPhQ',
                'UCfDPpLHN-7_6cctaU8CgPhQ',
                'https://www.youtube.com/channel/UCfDPpLHN-7_6cctaU8CgPhQ',
                RssChannel::TYPE_YOUTUBE,
            ],
            [
                'StopGame.Ru',
                'http://www.youtube.com/feeds/videos.xml?channel_id=UCq7JZ8ATgQWeu6sDM1czjhg',
                'UCq7JZ8ATgQWeu6sDM1czjhg',
                'https://www.youtube.com/channel/UCq7JZ8ATgQWeu6sDM1czjhg',
                RssChannel::TYPE_YOUTUBE,
            ],
            [
                'GohaMedia',
                'http://www.youtube.com/feeds/videos.xml?channel_id=UCww7wZ5uMaN5zIeq3srIgjA',
                'UCww7wZ5uMaN5zIeq3srIgjA',
                'https://www.youtube.com/channel/UCww7wZ5uMaN5zIeq3srIgjA',
                RssChannel::TYPE_YOUTUBE,
            ],
            [
                'MMORPG.SU. Онлайн игры',
                'http://www.youtube.com/feeds/videos.xml?channel_id=UCxxdTg_nqFdADGrz2eJ6hGA',
                'UCxxdTg_nqFdADGrz2eJ6hGA',
                'https://www.youtube.com/channel/UCxxdTg_nqFdADGrz2eJ6hGA',
                RssChannel::TYPE_YOUTUBE,
            ],
        ]);

    }

    public function safeDown() {
        $this->dropColumn('{{%rss_channel}}', 'type');
        $this->dropColumn('{{%rss_channel}}', 'channel_id');
        $this->alterColumn('{{%rss_channel}}', 'title_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'description_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'pub_date_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'link_tag', $this->string(20)->notNull());
        $this->alterColumn('{{%rss_channel}}', 'rss_channel_url', $this->string(255)->notNull());
    }

}
