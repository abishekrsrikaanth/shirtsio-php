<?php
require_once("Shirtsio.php");
/************ Account *************/
// get api key

$account_resp= Account::auth(array('username' => 'deantest', 'password' => 'Pa$$w0rd'));
print_r($account_resp["api_key"]);

/************End Account ************/

Shirtsio::setApiKey($account_resp['api_key']);

/************ quote /************/
$quote_resp = Quote::get_quote(Array('garment[0][product_id]'=> 3, 'garment[0][color]'=> 'White', 'garment[0][sizes][med]'=> 100,
                          'print[front][color_count]'=> 5));
print_r($quote_resp['subtotal']);

/************ End quote /************/

/************ balance /************/
//get balance

$balance_resp = Balance::get_balance();
print_r($balance_resp['balance']);

/************ End balance ************/

/************Order /************/
//place order

$art_work_file_front = realpath("./front.png");
$proof_file_front = realpath("./front.jpg");
$art_work_file_back = realpath("./back.png");
$proof_front_file_back = realpath("./back.jpg");

$data = array(
        'test'=> True, 
        'price'=> 79.28,
        'print[back][color_count]'=> 4, 
        'print[back][colors][0]'=> "101C", 
        'print[back][colors][1]'=> '107U',
        'addresses[0][name]'=> 'John Doe', 
        'addresses[0][address]'=> '123 Hope Ln.',
        'addresses[0][city]'=> 'Las Vegas', 
        'addresses[0][state]'=> 'Nevada', 
        'addresses[0][country]'=> 'US',
        'addresses[0][zipcode]'=> '12345', 
        'addresses[0][batch]'=> 1,
        'addresses[0][sizes][med]'=> 2,
        'addresses[0][sizes][lrg]'=> 2,
        'print_type'=> 'Digital Print', 
        'ship_type'=> 'Standard',
        'garment[0][product_id]'=> 2, 
        'garment[0][color]'=> "White",
        'garment[0][sizes][med]'=> 2, 
        'garment[0][sizes][lrg]'=> 2, 
        'print[front][color_count]'=> 5);
$files = array(
        'print[front][artwork]'=> '@'.$art_work_file_front, 
        'print[front][proof]'=> '@'.$proof_file_front,
        'print[back][artwork]'=> '@'.$art_work_file_back, 
        'print[back][proof]'=> '@'.$proof_front_file_back);
$order_resp = Order::place_order($data, $files);
print_r($order_resp);
print_r($order_resp['order_id']);

// get order status

//$order_status_resp = Order::get_order_status('9999999');
//print_r($order_status_resp);

/************ End Order /************/

/************ Products /************/
//list categories

$categories_resp = Products::list_categories();
$categories = json_decode($categories_resp,true);
print_r($categories);
echo $categories[0]['category_id'];

// list products

$products_resp = Products::list_products($categories[0]['category_id']);
$products=json_decode($products_resp,true);
print_r($products);

$product_resp = Products::get_product($products[0]['product_id']);
$product =json_decode($product_resp,true);
print_r($product);

// inventory count

$inventory_resp = Products::inventory_count($products[0]['product_id'], 'White', 'CA');
print_r($inventory_resp);

/************ End Products /************/

/************ Webhooh /************/
// register webhook

$register_webhook_resp = Webhook::add_webhook("http://test_webhook");
print_r($register_webhook_resp);

// list webhook

$list_webhook_resp = Webhook::list_webhook();
print_r($list_webhook_resp);

// delete webhook

$delete_webhook_resp = Webhook::delete_webhook("http://test_webhook");
print_r($delete_webhook_resp);

/************ End Webhooh /************/

/************ Payment /************/
// check payment

$TEST_CHECK_PAYMENT = array(
    'name'=>'John Doe',
    'company'=> 'Acme Corp',
    'address1'=> '1 Test Drive',
    'city'=> 'Test Town',
    'state'=> 'New York',
    'zip'=> '99999',
    'amount'=> '500.23',
    'payment_type'=>'check',
    'account_number'=> '39494949',
    'routing_number'=> '5903495',
    'account_type'=> 'C',
);

$TEST_CREDIT_PAYMENT = array(
    'name'=> 'Johnny Appleseed',
    'company'=> 'Bigcorp',
    'address1'=>'1 Hope Lane',
    'city'=>'Big city',
    'state'=> 'Iowa',
    'zip'=>'99999',
    'amount'=> '1000',
    'payment_type'=> 'credit_card',
    'card_number'=> '4242424242424242',
    'expiration'=> '0215',
    'cvc'=> '123',
);
$payment=new Payment();
$check_payment_resp =  $payment->payment($TEST_CHECK_PAYMENT);
$credit_payment_resp = $payment->payment($TEST_CREDIT_PAYMENT);
print_r($check_payment_resp);
print_r($credit_payment_resp);

// get payment status

$payment_status_resp = $payment->get_payment_status(array('transaction_id'=>'329402'));
print_r($payment_status_resp);

// add payment webhook url

$payment_status_resp2 = $payment->update_payment_url(array('url'=> "http://yourappurl"));
print_r($payment_status_resp2);

/************ End Payment ************/
?>