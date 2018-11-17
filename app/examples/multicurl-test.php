<?php

require_once realpath(__DIR__ . '/../config/config.php');

// creating array of urls to test
for ( $i = 0 ; $i < 100; $i++ ) {
    //https://www.imdb.com/movies-coming-soon/2019-01/?ref_=cs_dt_nx
    $urls [] = 'https://httpbin.org/get?i=' . $i;
    //$urls [] = 'https://www.imdb.com/movies-coming-soon/2019-0' . $i . '/?ref_=cs_dt_nx';
}

// Allows the processing of multiple cURL handles asynchronously
$multi = curl_multi_init();
$handles = [];

// building individual requests but do not execute them!
foreach ($urls as $url) {
    $ch = curl_init($url);
    //return as string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // do not return headers
    curl_setopt($ch, CURLOPT_HEADER, false);
    // add more options here


    // Add a normal cURL handle to a cURL multi handle
    curl_multi_add_handle($multi, $ch);
    // save channels in array for further usage
    $handles[$url] = $ch;
}

// число работающих процессов.
$running = null;
// curl_multi_exec запишет в переменную running количество еще не завершившихся
// процессов. Пока они есть - продолжаем выполнять запросы.
do { curl_multi_exec($multi, $running); } while($running > 0);

// Собираем из всех созданных механизмов результаты, а сами механизмы удаляем
foreach ($handles as $channel) {
    $html = curl_multi_getcontent($channel);
    curl_multi_remove_handle($multi, $channel);
}
// Освобождаем память от механизма мультипотоков
curl_multi_close($multi);