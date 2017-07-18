
<?php
$count = explode('-', $search);
if(count($count) > 2){
    $search = str_replace('-', ' ', $search);
    $search = str_replace(' ', '_', $search);
}else{
    $search = $count[0];
}

//echo  $search;


// создание нового ресурса cURL
$ch = curl_init();
// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, "http://logs.snhrs.ru/web/index.php/api?search=".$search);
curl_setopt($ch, CURLOPT_HEADER, 0);
// загрузка страницы и выдача её браузеру
echo curl_exec($ch);
// завершение сеанса и освобождение ресурсов
curl_close($ch);
?>



