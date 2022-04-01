<?php

namespace console\controllers;
use yii\console\Controller;
use yii\helpers\Html;
use common\components\notifications\Notifications;
use common\models\RssChannel;
use common\models\News;
use common\models\AppLogs;

/**
 * Новости
 */
class NewsController extends Controller {
    
    private $count_days = 30;

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
        $rss_channels = RssChannel::find()->where(['type' => RssChannel::TYPE_NEWS])->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $tags = $rss_channel->rssTags;
                if (!$this->isUrlAvailable($url)) {
                    continue;
                }
                try {
                    $feeds = file_get_contents($url, false, stream_context_create($arr_context_options));
                    $rss = @simplexml_load_string($feeds);
                    if ($rss === false) {
                        continue;
                    }
                    $xml_rss = $rss;
                    if ($tags['root'] !== '') {
                        $xml_rss = $rss->{$tags['root']};
                    }
                    if ($tags['item'] !== '') {
                        $xml_rss = $xml_rss->{$tags['item']};
                    }
                    foreach ($xml_rss as $item) {
                        if (!array_key_exists('pub_date', $tags)) {
                            continue;
                        }
                        if (!property_exists($item, $tags['pub_date'])) {
                            continue;
                        }
                        $date_pub = new \DateTime($item->{$tags['pub_date']});
                        $current_date = new \DateTime('NOW');
                        // Если разница между текущей датой и датой публикации больше $count_days дней, то такую новость не запоминаем
                        if ($current_date->diff($date_pub)->d > $this->count_days) {
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
                            $news->{$key} = trim(Html::decode($item->{$tag}));
                            if ($tags['image'] == null) {
                                $description = Html::decode($item->{$tags['description']});
                                trim(preg_match('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $description, $matches));
                                $news->image = $matches ? trim($matches[1]) : null;
                            } elseif ($tags['image'] !== 'image') {
                                $image_url = isset($item->{$tags['image']}['url'])
                                    ? (string) trim($item->{$tags['image']}['url'])
                                    : null;
                                $news->image = trim($image_url);
                            }
                            if ($tags['link']) {
                                $news->link = isset($item->{$tags['link']}['href'])
                                    ? (string) trim($item->{$tags['link']}['href'])
                                    : (string) $item->{$tags['link']};
                            }
                        }
                        if (!$news->save()) {
                            $errors = $news->getFirstErrors();
                            AppLogs::addLog(
                                'Ошибка сохранения новости из ленты: ' .
                                $rss_channel->rss_channel_name .
                                ' Ошибка: ' . reset($errors)
                            );
                            continue;
                        }
                    }
                } catch (\Throwable $th) {
                    AppLogs::addLog('Ошибка чтения ленты новостей: ' . $rss_channel->rss_channel_name . 'Ошибка: ' . $th->getMessage());
                    continue;
                }
            }
        }
    }

    /**
     * Обновление новостей
     * Для ежедневного запуска с 8 утра до 23:00
     * каждый час
     */
    public function actionCheck() {
        $arr_context_options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];
        $count_news = 0;
        $rss_channels = RssChannel::find()->where(['type' => RssChannel::TYPE_NEWS])->all();
        if ($rss_channels) {
            foreach ($rss_channels as $rss_channel) {
                $url = $rss_channel->rss_channel_url;
                $tags = $rss_channel->rssTags;
                if (!$this->isUrlAvailable($url)) {
                    continue;
                }
                try {
                    $feeds = file_get_contents($url, false, stream_context_create($arr_context_options));
                    $rss = @simplexml_load_string($feeds);
                    if ($rss === false) {
                        continue;
                    }
                    $xml_rss = $rss;
                    if ($tags['root'] !== '') {
                        $xml_rss = $rss->{$tags['root']};
                    }
                    if ($tags['item'] !== '') {
                        $xml_rss = $xml_rss->{$tags['item']};
                    }
                    foreach ($xml_rss as $item) {
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
                        // Если разница между текущей датой и датой публикации больше count_days дней, то такую новость не запоминаем
                        if ($current_date->diff($date_pub)->d > $this->count_days) {
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
                            $news->{$key} = trim(Html::decode($item->{$tag}));
                            if ($tags['image'] == null) {
                                $description = Html::decode($item->{$tags['description']});
                                trim(preg_match('/<img[^>]+src="?\'?([^"\']+)"?\'?[^>]*>/i', $description, $matches));
                                $news->image = $matches ? trim($matches[1]) : null;
                            } elseif ($tags['image'] !== 'image') {
                                $image_url = isset($item->{$tags['image']}['url'])
                                    ? (string) trim($item->{$tags['image']}['url'])
                                    : null;
                                $news->image = $image_url;
                            }
                            if ($tags['link']) {
                                $news->link = isset($item->{$tags['link']}['href'])
                                    ? (string) trim($item->{$tags['link']}['href'])
                                    : (string) $item->{$tags['link']};
                            }
                        }
                        if (!$news->save()) {
                            continue;
                        }

                        $count_news++;
                    }
                    if ($count_news > 0) {
                        AppLogs::addLog("RSS лента {$rss_channel->rss_channel_name}, обновление списка новостей, новых новостей {$count_news}");
                    }
                    $count_news = 0;
                } catch (\Throwable $th) {
                    AppLogs::addLog('Ошибка чтения ленты новостей: ' . $rss_channel->rss_channel_name . 'Ошибка: ' . $th->getMessage());
                    continue;
                }
            }
        }
        $this->sendNewsNotification();
    }

    /**
     * Удаление не актуальных новостей
     * Новость актуальна 3 дня от текущей даты
     * 
     * Для ежедневного запуска в 00:00
     */
    public function actionRemove() {
        $news = News::find()
            ->joinWith([
                'rss' => function ($query) {
                    $query->andWhere(['type' => RssChannel::TYPE_NEWS]);
                },
            ])
            ->all();
        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $count_news = 0;
        if ($news) {
            foreach ($news as $news_item) {
                $date_pub = new \DateTime($news_item->pub_date, new \DateTimeZone('Europe/Moscow'));
                // Если разница между текущей датой и датой публикации больше count_days дней, то такую новость удаляем
                if ($current_date->diff($date_pub)->days > $this->count_days) {
                    $count_news++;
                    if (!$news_item->delete()) {
                        continue;
                    }
                }
            }
            if ($count_news > 0) {
                AppLogs::addLog("Удаление неактуальных новостей, количество {$count_news}");
            }
        }
    }
    
    private function sendNewsNotification() {

        $current_date = new \DateTime('NOW', new \DateTimeZone('Europe/Moscow'));
        $current_day_of_week = \common\models\User::DAYS_OF_WEEK[$current_date->format('N') - 1];
        $current_time = $current_date->format('H');
        
        $notification = new Notifications(
            Notifications::NEWS_TYPE, 
            null, null,
            ['day' => $current_day_of_week, 'cur_hour' => $current_time]
        );
        
        $notification->createNotification();
        
    }

    private function isUrlAvailable($url) {
        $html_brand = $url;
        $ch = curl_init();

        $options = [
            CURLOPT_URL => $html_brand,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 180,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_MAXREDIRS => 10,
        ];
        curl_setopt_array( $ch, $options );
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode < 200 || $httpCode >= 300) {
            AppLogs::addLog("Ошибка чтения RSS-ленты: $html_brand. Код ответа: " . $httpCode);
            return false;
        }

        curl_close($ch);
        return true;
    }

}
