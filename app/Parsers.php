<?php

namespace App;

use Symfony\Component\DomCrawler\Crawler;

class Parsers
{
    /**
     * @param $html - html code of specific page with proxies and ports
     * @return array of proxies and ports for checkout. With key-element pair
     * like this: 0 => "186.178.10.20:9999"
     */
    public function parseProxiesList($html)
    {
            $crawler = new Crawler($html);
            $proxyAndPort = [];
            $crawler = $crawler
                ->filter('table.proxylist_table > tbody > tr > td:first-of-type > a')
                ->each(function ($node) use(&$proxyAndPort) {
                    $url = $node->attr('href');
                    $urlArr = explode('/', $url);
                    $proxyAndPort[] = end($urlArr);
                });
            return $proxyAndPort;
    }
}
