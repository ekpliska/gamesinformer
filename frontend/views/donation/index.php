<?php
$mrh_login = "videogames";
$mrh_pass1 = "JaEn605lZRj6rnvKIyF9";
$mrh_pass1_test = 'iiomfX21I4ejl2LVh7oH';
$inv_id = 0;
$inv_desc = "Пожертвование проекту GamePlay";
$def_sum = "100";
$IsTest = 1;
$crc = md5("$mrh_login::$inv_id:$mrh_pass1_test");
echo "
    <html>
        <script language=JavaScript " .
            "src='https://auth.robokassa.ru/Merchant/PaymentForm/FormFLS.js?" .
            "MerchantLogin=$mrh_login" .
            "&DefaultSum=$def_sum" .
            "&InvoiceID=$inv_id" .
            "&Description=$inv_desc" .
            "&IsTest=$IsTest" .
            "&SignatureValue=$crc'>
        </script>
    </html>";
?>