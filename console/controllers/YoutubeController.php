<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Html;
use common\models\RssChannel;
use common\models\News;
use common\models\AppLogs;

/**
 * YouTube новости
 */
class YoutubeController extends Controller {

    /**
     * Первоначальная загрузка YouTube новостей из лент
     */
    public function actionLoad() {

        $rss_channels = RssChannel::find()->where(['type' => RssChannel::TYPE_YOUTUBE])->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $xml = @simplexml_load_file($url);
                if (!$xml) {
                    continue;
                }
                foreach ($xml->entry as $item) {
                    if (!$this->createNews($item, $rss_channel->id)) {
                        continue;
                    }
                }

            }
        }
        $new_log = new AppLogs();
        $new_log->value_1 = 'Сформирован первоначальный список YouTube новостей на сервере';
        $new_log->save(false);
    }

    /**
     * Обновление YouTube новостей
     * Для ежедневного запуска с 8 утра до 23:00
     * каждые 4 часа
     */
    public function actionCheck() {
        $count_news = 0;
        $rss_channels = RssChannel::find()->where(['type' => RssChannel::TYPE_YOUTUBE])->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $rss = @simplexml_load_file($url);
                if (!$rss) {
                    continue;
                }
                foreach ($rss->entry as $item) {
                    $link_news = (string)$item->link->attributes()->href;
                    if (News::findByLink($link_news)) {
                        continue;
                    }

                    $count_news++;

                    if (!$this->createNews($item, $rss_channel->id)) {
                        continue;
                    }

                }
                if ($count_news > 0) {
                    $new_log = new AppLogs();
                    $new_log->value_1 = "YouTube канал {$rss_channel->rss_channel_name}, обновление списка видео новостей, новых видео {$count_news}";
                    $new_log->save(false);
                }
                $count_news = 0;
            }
        }
    }

    private function createNews($entry, $rss_channel_id) {
        $published = new \DateTime($entry->published);
        $news = new News();
        $news->title = (string)$entry->title;
        $news->link = (string)$entry->link->attributes()->href;
        $news->rss_channel_id = $rss_channel_id;
        $news->pub_date = $published->format('Y-m-d H:i:s');
        $media = $entry->children('media', true);
        foreach ($media as $el) {
            $news->description = (string)$el->description;
            foreach ($el->thumbnail as $i) {
                $news->image = (string)$i->attributes()->url;
            }
        }
        if ($news->save()) {
            return true;
        } else {
            return false;
        }
    }

}