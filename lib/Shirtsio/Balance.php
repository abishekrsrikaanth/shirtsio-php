<?php
// This is the encapsulation class for Balance requests to Shirt.io       
class Balance extends Shirtsio_ApiResource
{
        public static $url_balance = "internal/integration/balance/";
        //public static $url_credit_limit = "internal/integration/credit_limit/";
        public static function get_balance()
        {
        // https://shirts.io/api/v1/internal/integration/balance/
        return self::do_request(self::$url_balance);
        }
}