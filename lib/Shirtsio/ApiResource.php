<?php

class Shirtsio_ApiResource extends Shirtsio
{

	private static function array_params($no_api_key, $params,$files)
	{
		$api_key_param = array('api_key' => Shirtsio::$apiKey);
			
		if (is_null($no_api_key)) {
			return $params;
		}

		if (!is_null($files)) {
			$params=array_merge($params,$files);
		}

		if (!is_null($params)) {
			return array_merge($api_key_param, $params);
		}
			
		return $api_key_param;
	}

	private static function _validateCall($params = null, $apiKey = null)
	{
		if ($params && !is_array($params))
		throw new Shirtsio_Error("You must pass an array as the first argument to Shirts.io API method calls.  (HINT: an example call to create a charge would be: \"ShirtsioCharge::create(array('amount' => 100, 'currency' => 'usd', 'card' => array('number' => 4242424242424242, 'exp_month' => 5, 'exp_year' => 2015)))\")");
		if ($apiKey && !is_string($apiKey))
		throw new Shirtsio_Error('The second argument to Shirts.io API method calls is an optional per-request apiKey, which must be a string.  (HINT: you can set a global apiKey by "Shirtsio::setApiKey(<apiKey>)")');
	}

	public static function do_request($url = null, $params = null, $method = 'get', $files = null, $no_api_key = false)
	{
		$requestor = new Shirtsio_ApiRequestor(Shirtsio::$apiKey);
		Shirtsio_ApiResource::_validateCall($params, Shirtsio::$apiKey);
		$params = Shirtsio_ApiResource::array_params($no_api_key, $params,$files);
		$url = $requestor->apiUrl($url);
		return $requestor->request($url, $params, $method);
		//return $requestor->request($url, $params, $method, $files);
	}
}

