<?php

class Curl {
    public function post($url, $post = NULL, array $options = [], $basicUser, $type = false) {
        $type = $type  ? http_build_query($post) : json_encode($post);
        $defaults = array(
            CURLOPT_POST           => 1,
            CURLOPT_HEADER         => 0,
            CURLOPT_URL            => $url,
            CURLOPT_FRESH_CONNECT  => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE   => 1,
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_POSTFIELDS     => $type,
            CURLOPT_USERPWD        => "{$basicUser}:",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER     => [ 'Content-Type' => 'application/json; charset=UTF-8', 'accept' => 'application/json' ]
        );    
        $request = curl_init();

        curl_setopt_array($request, ($options + $defaults));
        if( !$result = curl_exec($request)) { trigger_error(curl_error($request)); }
        curl_close($request);

        return $result;
    }

    public function get($url, array $get = NULL, array $options = []) {
        $defaults = array(
            CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). json_encode($get),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 4
        );
    
        $request = curl_init();
        curl_setopt_array($request, ($options + $defaults));
        if( !$result = curl_exec($request)) { trigger_error(curl_error($request)); }
        curl_close($request);

        return $result;
    }
}