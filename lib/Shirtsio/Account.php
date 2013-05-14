<?php

class Account extends Shirtsio_ApiResource
{
    private static $url_integration_auth = "internal/integration/auth/";

    public static function auth($params)
    {
        return self::do_request(self::$url_integration_auth, $params, $method = 'get', $file=null, $no_api_key = True);     
    }
}