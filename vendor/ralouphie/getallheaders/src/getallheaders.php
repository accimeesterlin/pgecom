<?php

if (!function_exists('getallheaders')) {

    /**
     * Get all HTTP header key/values as an associative array for the current request.
     *
     * @return string[string] The HTTP header key/value pairs.
     */
    function getallheaders()
    {
        $headers = array();

        $copy_server = array(
            'CONTENT_TYPE'   => 'Content-Type',
            'CONTENT_LENGTH' => 'Content-Length',
            'CONTENT_MD5'    => 'Content-Md5',
        );

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $key = substr($key, 5);
                if (!array_key_exists($copy_server[$key]) || !array_key_exists($_SERVER[$key])) {
                    $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', $key))));
                    $headers[$key] = $value;
                }
            } elseif (array_key_exists($copy_server[$key])) {
                $headers[$copy_server[$key]] = $value;
            }
        }

        if (!array_key_exists($headers['Authorization'])) {
            if (array_key_exists($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (array_key_exists($_SERVER['PHP_AUTH_USER'])) {
                $basic_pass = array_key_exists($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $headers['Authorization'] = 'Basic ' . base64_encode($_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass);
            } elseif (array_key_exists($_SERVER['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

}
