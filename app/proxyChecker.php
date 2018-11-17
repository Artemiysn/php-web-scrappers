<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 17.11.18
 * Time: 23:22
 */

namespace App;


class proxyChecker
{

    /**
     * @param $proxyList - array of proxies to check in format: 0 =>127.1.1.1:8400
     * @return array - returns only viable proxies
     */
    public function checker($proxyList)
    {

        // временно обрежем  список
        //    $proxyList = array_slice($proxyList, 0 , 5);
        $checkedProxyList = [];
        foreach ($proxyList as $proxy) {
            $ch = curl_init("https://httpbin.org/ip");
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $handle = curl_exec($ch);
            $matches = $this->_checkReturn($handle, $proxy);
            if ($matches) {
                $checkedProxyList[] = $proxy;
            }
            curl_close($ch);
        }
        return $checkedProxyList;
    }

    /**
     * @param $returnString - response string from curl request
     * @param $proxyString - proxy in format like this: 127.1.1.1:8400
     * @return bool - if response is a json with request proxy returns true
     */
    private function _checkReturn($returnString, $proxyString) {
        json_decode($returnString);
        if (json_last_error() == JSON_ERROR_NONE) {
            $proxyWoPort = strtok($proxyString, ':');
            if (strpos($returnString, $proxyWoPort) !== false) {
                return true;
            }
        }
        return false;
    }

}