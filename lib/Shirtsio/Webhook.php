<?php
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