<?php

namespace console\controllers;
use yii\console\Controller;
use yii\helpers\Html;
use common\models\RssChannel;
use common\models\News;
use common\models\AppLogs;

/**
 * Новости
 */
class NewsController extends Controller {

    /**
     * Первоначальная загрузка новостей из лент
     */
    public function actionLoad() {

        $arr_context_options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        $rss_channels = RssChannel::find()->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $tags = $rss_channel->rssTags;
                $feeds = file_get_contents($url, false, stream_context_create($arr_context_options));
                if (!$feeds) {
                    continue;
                }
                $rss = @simplexml_load_string($feeds);
                if ($rss === false) {
                    continue;
                }
                foreach ($rss->channel->item as $item) {
                    if (!array_key_exists('pub_date', $tags)) {
                        continue;
                    }
                    if (!property_exists($item, $tags['pub_date'])) {
                        continue;
                    }
                    $date_pub = new \DateTime($item->{$tags['pub_date']});
                    $current_date = new \DateTime('NOW');
                    // Если разница между текущей датой и датой публикации больше 3 дней, то такую новость не запоминаем
                    if ($current_date->diff($date_pub)->d > 3) {
                        continue;
                    }
                    $news = new News();
                    $news->rss_channel_id = $rss_channel->id;
                    foreach ($tags as $key => $tag) {
                        if ($key === 'pub_date') {
                            $news->pub_date = $date_pub->format('Y-m-d H:i:s');
                        }
                        if (!property_exists($item, $tag)) {
                            continue;
                        }
                        $news->{$key} = Html::decode($item->{$tag});
                        if ($tags['image'] == null) {
                            $description = Html::decode($item->{$tags['description']});
                            preg_match('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $description, $matches);
                            $news->image = $matches ? $matches[1] : null;
                        } elseif ($tags['image'] !== 'image') {
                            $image_url = isset($item->{$tags['image']}['url']) ? (string)$item->{$tags['image']}['url'] : null;
                            $news->image = $image_url;
                        }
                    }
                    if (!$news->save()) {
                        continue;
                    }
                }
            }
        }
        $new_log = new AppLogs();
        $new_log->value_1 = 'Сформирован первоначальный список новостей на сервере';
        $new_log->save(false);
        
    }
    
    /**
     * Обновление новостей
     * Для ежедневного запуска с 8 утра до 23:00
     * каждые 4 часа
     */
    public function actionCheck() {
        $arr_context_options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        $count_news = 0;
        $rss_channels = RssChannel::find()->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $tags = $rss_channel->rssTags;
                $feeds = file_get_contents($url, false, stream_context_create($arr_context_options));
                $rss = @simplexml_load_string($feeds);
                if ($rss === false) {
                    continue;
                }
                foreach ($rss->channel->item as $item) {
                    if (!array_key_exists('title', $tags)) {
                        continue;
                    }
                    $title_news = $item->{$tags['title']};
                    if (News::checkNews($title_news)) {
                        continue;
                    }
                    if (!array_key_exists('pub_date', $tags)) {
                        continue;
                    }
                    if (!property_exists($item, $tags['pub_date'])) {
                        continue;
                    }
                    $date_pub = new \DateTime($item->{$tags['pub_date']});
                    $current_date = new \DateTime('NOW');
                    // Если разница между текущей датой и датой публикации больше 3 дней, то такую новость не запоминаем
                    if ($current_date->diff($date_pub)->d > 3) {
                        continue;
                    }
                    $news = new News();
                    $news->rss_channel_id = $rss_channel->id;
                    foreach ($tags as $key => $tag) {
                        $count_news++;
                        if ($key === 'pub_date') {
                            $news->pub_date = $date_pub->format('Y-m-d H:i:s');
                        }
                        if (!property_exists($item, $tag)) {
                            continue;
                        }
                        $news->{$key} = Html::decode($item->{$tag});
                        if ($tags['image'] == null) {
                            $description = Html::decode($item->{$tags['description']});
                            preg_match('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $description, $matches);
                            $news->image = $matches ? $matches[1] : null;
                        } elseif ($tags['image'] !== 'image') {
                            $image_url = isset($item->{$tags['image']}['url']) ? (string)$item->{$tags['image']}['url'] : null;
                            $news->image = $image_url;
                        }
                    }
                    if (!$news->save()) {
                        continue;
                    }
                }
            }
            $new_log = new AppLogs();
            $new_log->value_1 = "RSS лента {$rss_channel->rss_channel_name}, обновление списка новостей, новых новостей {$count_news}";
            $new_log->save(false);
            $count_news = 0;
        }
    }
    
    /**
     * Удаление не актуальных новостей
     * Новость актуальна 3 дня от текущей даты
     * 
     * Для ежедневного запуска в 00:00
     */
    public function actionRemove() {
        $news = News::find()->all();
        $current_date = new \DateTime('NOW');
        $count_news = 0;
        if ($news) {
            foreach ($news as $news_item) {
                $date_pub = new \DateTime($news_item->pub_date);
                // Если разница между текущей датой и датой публикации больше 3 дней, то такую новость удаляем
                if ($current_date->diff($date_pub)->d > 3) {
                    if (!$news_item->delete()) {
                        continue;
                    }
                }
            }
            $new_log = new AppLogs();
            $new_log->value_1 = "RSS лента {$news_item->rss->rss_channel_name}, удаление неактуальных новостей, количество {$count_news}";
            $new_log->save(false);
        }
    }

}
