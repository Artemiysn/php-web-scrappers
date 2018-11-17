<?php

require_once realpath(__DIR__ . '/../config/config.php');

function request ( $url, $postdata, $cookiefile) {

    $ch = curl_init($url);
    // return as string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // allow redirects
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // set user agent as if we are browser
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
    // get and upload cookies to/from file for simulating real user authentification
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);

    // make it faster by disabling ssl protocol
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


    //  we should add fixed timeout because request from proxy can last eternity
    // curl_setopt($ch, CURL_TIMEOUT, 0);
    // curl_setopt($ch, CURL_CONNECTTIMEOUT, 0);
    // proxy we want to use
    //curl_setopt($ch, CURLOPT_PROXY, '111.');
    // proxy type
    //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);


    // if we have some post data to send, we put it in curl options
    if ($postdata) {

        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postdata);

    }

    $html = curl_exec($ch);
    return $html;

}

file_put_contents($config['cookiefile'], '');

//$html = request('https://www.reddit.com/login', null , $config['cookiefile']);



$post = [
    'op' => 'login',
    'dest' => 'https://www.reddit.com',
    'user' => $config['user'],
    'passwd' => $config['password']


];

$html = request('https://www.reddit.com/post/login', $post, $config['cookiefile']);

echo $html;