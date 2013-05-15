<?php
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
     