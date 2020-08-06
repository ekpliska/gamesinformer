<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <link rel="icon" href="/favicon.png" sizes="32x32" type="image/png">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <?php $this->head() ?>
</head>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="donation-page">
        <h4>
            Если Вам нравится наш проект и Вы хотите побыстрее увидеть что еще мы подготовили для Вас, то можете рассказать о нас своим друзьям или поддержать донатом!
        </h4>
        <?= $content ?>
        <div class="donation-page__footer">
            <div>
                <?= Html::img('@web/images/logo.png', ['alt' => 'logo']) ?>
            </div>
            <div>
                <p>
                    &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>,
                    <a href="mailto:inbox@gamenotificator.net">inbox@gamenotificator.net</a>
                </p>
                <p>
                    <a class="btn btn-vk" href="https://vk.com/gamenotificator" target="_blank">
                        <i class="fab fa-vk"></i>
                    </a>
                    <a class="btn btn-vk" href="https://play.google.com/store/apps/details?id=com.gamenotificator" target="_blank">
                        <i class="fab fa-google-play"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>