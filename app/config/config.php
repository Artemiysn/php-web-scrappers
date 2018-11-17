<?php

$dotenv = new Dotenv\Dotenv(realpath(__DIR__ . '/../../'));
$dotenv->load();

return $config = [
    'cookieFile' => realpath(__DIR__ . '/../cookies/cookies.txt'),
    'proxieFile' => realpath(__DIR__ . '/../proxies.txt'),
    'user' => getenv('username'),
    'password' => getenv('password')
];