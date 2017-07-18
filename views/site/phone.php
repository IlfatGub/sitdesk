

<?php
// создание нового ресурса cURL
$ch = curl_init();
// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, "http://phone.snhrs.ru/web/index.php/api/index?search=".$search);
//curl_setopt($ch, CURLOPT_HEADER, 0);
// загрузка страницы и выдача её браузеру

$str = curl_exec($ch);
// завершение сеанса и освобождение ресурсов
curl_close($ch);
?>


<?php
// создание нового ресурса cURL
$ch = curl_init();
// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, "http://10.224.178.80/phone.a-consalt.ru/web/index.php/api/index?search=".$search);
curl_setopt($ch, CURLOPT_HEADER, 0);
// загрузка страницы и выдача её браузеру
curl_exec($ch);
// завершение сеанса и освобождение ресурсов
curl_close($ch);
?>