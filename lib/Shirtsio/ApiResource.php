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
       
        //print_r( $api_key_param);
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

// This is the encapsulation class for Payment requests to Shirt.io
class Payment extends Shirtsio_ApiResource
{
    public static $url_payment = "payment/";
    public static $url_payment_status = "payment/status/";

    public function payment($params)
    {
        // https://shirts.io/api/v1/payment/
        return self::do_request(self::$url_payment, $params, $method='post');
    }
     public function  update_payment_url($params){
        // https://shirts.io/api/v1/payment/status/
        return self::do_request(self::$url_payment_status, $params, $method='post');
     }
   
     public function  get_payment_status($params){
        // https://shirts.io/api/v1/payment/status/
        return self::do_request(slef::$url_payment_status, $params, $method='get');
     }
}
// This is the encapsulation class for Order requests to Shirt.io
class Order extends Shirtsio_ApiResource{
     public static $url_order = "order/";
     public static $url_status = "status/";

     public static function place_order($params, $files){
        return self::do_request(self::$url_order, $params, $method='post',$files,$no_api_key = false);
     }
     public static function get_order_status($order_id){
        // https://shirts.io/api/v1/status/{Order_ID}
        $url = self::$url_status.$order_id."/";
        return self::do_request($url);
     }

}       

// This is the encapsulation class for products requests to Shirt.io
class Products extends Shirtsio_ApiResource{
     public static $url_products = "products/";
     public static $url_category = "products/category/";

    public static function list_categories(){
        // https://shirts.io/api/v1/products/category/
        return self::do_request(self::$url_category);
    }
    public static function list_products($category_id){
        // https://shirts.io/api/v1/products/category/{Category_ID}/
        $url = self::$url_category.$category_id."/";
        //echo $url;
        return self::do_request($url);
    }
     public static function get_product($product_id){
        // https://shirts.io/api/v1/products/{Product_ID}/
        $url = self::$url_products.$product_id."/";
        return self::do_request($url);
     }
    public static function inventory_count($product_id, $color, $state=null){
        $params = array('color'=> $color, 'state'=>$state);
        $inventory = null;
        // https://shirts.io/api/v1/products/{Product_ID}/
        $url = self::$url_products.$product_id."/";
        $result_inventory = self::do_request($url, $params);
        if ($result_inventory&&array_key_exists('inventory', $result_inventory)){
        //if ($result_inventory && in_array('inventory', $result_inventory)){
           $inventory = $result_inventory['inventory'];
        }
        return $inventory;
    }
}

// This is the encapsulation class for webhook registration£¬list£¬delete requests to Shirt.io
class Webhook extends Shirtsio_ApiResource{
      public static $url_webhook = "webhooks/";

     public static function add_webhook($listener_url){
        $url = self::$url_webhook."register"."/";
        $params =array('url'=>$listener_url);
        return self::do_request($url, $params, $method='post');
     }
     public static function delete_webhook($listener_url){
        $url = self::$url_webhook."delete"."/";
        $params =array('url'=>$listener_url);
        return self::do_request($url, $params, $method='post');
     }
    public static function list_webhook(){
        $url = self::$url_webhook."list"."/";
        return self::do_request($url);
    }
}