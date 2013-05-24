<?php
// This is the encapsulation class for Order requests to Shirt.io
class Order extends Shirtsio_ApiResource{
	public static $url_order = "order/";
	public static $url_status = "status/";

	public static function place_order($params, $files){
		return self::do_request(self::$url_order, $params, $method='post',$files,$no_api_key = false);
	}
	public static function get_order_status($params){
		$url = self::$url_status;
		return self::do_request($url,$params);
	}

}
