<?php

namespace App;

class CurlWrapper
{
    /**
     * wrapper for curl get request
     * @param $url - site url
     * @param $cookieFile - file with cookies if needed
     */
    public function curlGet($url, $cookieFile) {
        $ch = curl_init($url);
        // return as string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // allow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // Required for HTTP error codes to be reported via our call to curl_error($ch)
        curl_setopt($ch,CURLOPT_FAILONERROR,true);
        // set user agent as if we are browser
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
        // upload and get cookies to/from file for simulating real user authentification
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        // make it faster by disabling ssl protocol
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $html = curl_exec($ch);
        // catching errors
        if (curl_error($ch)) {
            $error_msg = curl_error($ch);
        }

        curl_close($ch);
        // if any errors happened stop the script
        if (isset($error_msg)) {
            echo 'Ooops! something happened: \n';
            echo $error_msg;
            die;
        }

        return $html;
    }

}