<?php

    use yii\helpers\Html;
?>
<div class="donation-page">
    <div class="comp_info">
        <p>
            Наше приложение для смартфонов - это не просто календарь релизов новых онлайн игр. 
            Это целый портал для настоящего игромана, в котором вы сможете:
        </p>
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                Следить за новостями крупнейших игровых СМИ прямо со своего смартфона без лишних 
                переходов на сторонние сайты или обсуждения.
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                Оставаться в курсе событий игрового мира. У вас будет свой собственный “карманный” календарь 
                релизов новинок, чтобы вы не пропустили на одного события в игровом мире.
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                Пользоваться мобильным путеводителем по игровым вселенным. Изучайте новинки игр, интересные 
                прошедшие гайды, следите за любимыми видеоиграми, ищите ранее вышедшие консоли по дате выхода.
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                Насладиться обширной базой игр платформера, ведь в нашем приложении собрано более 50 
                платформ и это только лучшие игры.
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                Вместе с нашим приложением “GamePlay” вы почувствуете себя частью игровой вселенной! 
                Только лучшие анонсы, обзоры, рецензии и трейлеры на видеоигры, свежие новости и онлайн клуб по интересам.
            </div>
        </div>
    </div>
    <h4>
        Если Вам нравится наш проект и Вы хотите побыстрее увидеть что еще мы подготовили для Вас, то можете рассказать о нас своим друзьям или поддержать донатом!
    </h4>
    <?php
        $mrh_login = "videogames";
        $mrh_pass1 = "lQ3K4Jd62IacyCCCWq0V";
        $inv_id = 0;
        $inv_desc = "Пожертвование проекту GamePlay";
        $def_sum = "100";
        $IsTest = 1;
        $crc = md5("$mrh_login::$inv_id:$mrh_pass1");
        echo "
        <html>
            <script language=JavaScript " .
                "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?" .
                "MerchantLogin=$mrh_login" .
                "&DefaultSum=$def_sum" .
                "&InvoiceID=$inv_id" .
                "&Description=$inv_desc" .
                "&SignatureValue=$crc'>
            </script>
        </html>";
    ?>
    <div class="donation-page__footer">
        <div>
            <?= Html::img('@web/images/logo.png', ['alt' => 'logo']) ?>
        </div>
        <div>
            <p>
                &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>,
                <a href="mailto:inbox@gamenotificator.net">inbox@gamenotificator.net</a>
                <a href="mailto:buslaev@gmail.com">buslaev@gmail.com</a>
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