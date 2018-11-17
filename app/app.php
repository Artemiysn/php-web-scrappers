<?php

namespace App;

require_once __DIR__ . '/config/config.php';

//make sure cookie file is empty
file_put_contents($config['cookieFile'], '');

$curlWrapper = new CurlWrapper();
// retrieving html of the page with socks proxies
$htmlSpysOne = $curlWrapper->curlGet('https://www.proxydocker.com/en/socks5-list/', $config['cookieFile']);

$parsers = new Parsers();

//parsing html from specific site
$uncheckedProxylist = $parsers->parseProxiesList($htmlSpysOne);

// add more proxies into $uncheckedProxylist array here. Don't overwrite array,
// just add more elements in a format like this: 0 => "186.178.10.20:9999"
// ...

// check our proxy list

$proxyChecker = new proxyChecker();

$checkedProxies = $proxyChecker->checker($uncheckedProxylist);

//make sure proxie file is empty
file_put_contents($config['proxieFile'], '');
// add checked proxies into file
file_put_contents($config['proxieFile'],  implode("\n", $checkedProxies) . "\n", FILE_APPEND);

echo "Checked Proxies:" . "\n";

dump($checkedProxies);
