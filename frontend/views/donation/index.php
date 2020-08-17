<?php
$mrh_login = $_ENV['mrh_login'];
$mrh_pass1 = $_ENV['mrh_pass1'];
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