<?php

use \Curl\MultiCurl;
use Symfony\Component\DomCrawler\Crawler;

require_once realpath(__DIR__ . '/../config/config.php');

$multi_curl = new MultiCurl();

$multi_curl->success(function ($instance) {
    //echo 'call to "' . $instance->url . '" was successful.' . "\n";
//    echo 'response: ' . $instance->response . "\n";
    $response = $instance->response;
    $crawler = new Crawler($response);
    $crawler->filter('.overview-top > h4 > a')->each(function ($node) {
        dump($node->html());
    });
});

$multi_curl->error(function ($instance) {
    echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
    echo 'error code: ' . $instance->errorCode . "\n";
    echo 'error message: ' . $instance->errorMessage . "\n";
});

$multi_curl->complete(function ($instance) {
    //echo 'call to "' . $instance->url . '" completed.' . "\n";
});

for ($i = 1; $i < 10; $i++) {
    $url = 'https://www.imdb.com/movies-coming-soon/2019-0' . $i;
    // вот здесь можно добавить курлы по отдельности ИЛИ опции с проксями
    $multi_curl->addGet($url);
}

$multi_curl->start();
