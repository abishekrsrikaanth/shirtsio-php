<?php

abstract class Shirtsio
{
    public static $apiKey;
    public static $apiBase = 'https://www.shirts.io/api/v1/';
    public static $apiVersion = null;
    const VERSION = '1.0.0';

    public static function getApiKey()
    {
        return self::$apiKey;
    }

    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }
}
