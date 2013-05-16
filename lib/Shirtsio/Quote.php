<?php
// This is the encapsulation class for quote requests to Shirt.io
class Quote extends Shirtsio_ApiResource
{
	public static $url_quote = "quote/";
	public static function  get_quote($params=null)
	{
		// https://shirts.io/api/v1/quote
		return self::do_request(self::$url_quote, $params);
	}
}
