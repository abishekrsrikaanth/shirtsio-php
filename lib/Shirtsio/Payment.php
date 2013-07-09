<?php
// This is the encapsulation class for Payment requests to Shirt.io
class Payment extends Shirtsio_ApiResource
{
	public static $url_payment = "payment/";
	public static $url_payment_status = "payment/status/";

	public function payment($params)
	{
		// https://www.shirts.io/api/v1/payment/
		return self::do_request(self::$url_payment, $params, $method='post');
	}
	public function  update_payment_url($params){
		// https://www.shirts.io/api/v1/payment/status/
		return self::do_request(self::$url_payment_status, $params, $method='post');
	}
	 
	public function  get_payment_status($params){
		// https://www.shirts.io/api/v1/payment/status/
		return self::do_request(self::$url_payment_status, $params, $method='get');
	}
}