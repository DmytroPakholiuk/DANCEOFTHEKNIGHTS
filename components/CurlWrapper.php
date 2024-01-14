<?php

namespace components;

use exceptions\NotFoundException;

class CurlWrapper
{
    public function getPage($url)
    {
        $curlHandle = curl_init($url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);

        $page = curl_exec($curlHandle);
        if(curl_getinfo($curlHandle,  CURLINFO_RESPONSE_CODE) === 404
            || curl_error($curlHandle)) {
            throw new NotFoundException("The link that you sent was not valid");
//            throw new NotFoundException("The link that you sent was not valid");
        }
        curl_close($curlHandle);

        return $page;
    }
}