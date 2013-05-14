<?php

class Shirtsio_ApiConnectionError extends Shirtsio_Error
{
    public function __construct($error, $http_status = null, $http_body = null, $json_body = null)
    {
        parent::__construct($error, $http_status, $http_body, $json_body);
    }
}
