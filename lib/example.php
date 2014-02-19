<?php
require_once("Shirtsio.php");

Shirtsio::setApiKey('0ef58f89c6c8d0ce3f71e4ab3537db4e24d6ac40');

/************ quote /************/
$quote_resp = Quote::get_quote(Array('garment[0][product_id]'=> 3, 'garment[0][color]'=> 'White', 'garment[0][sizes][med]'=> 100,
                          'print[front][color_count]'=> 5, 'third_party_shipping'=> 0));
print_r($quote_resp['subtotal']);

/************ End quote /************/


/************Order /************/
//place order

$art_work_file_front = realpath("./front.png");
$proof_file_front = realpath("./front.jpg");
$art_work_file_back = realpath("./back.png");
$proof_front_file_back = realpath("./back.jpg");

$data_ups = array(
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
        'addresses[0][third_party_ship_type]'=> 'ups',
        'third_party_shipping[0][account_type]'=> 'ups',
        'third_party_shipping[0][account_number]'=> 'ups1234567890',
        'print_type'=> 'DTG Print',
        'third_party_shipping'=> 1,
        'garment[0][product_id]'=> 2,
        'garment[0][color]'=> "White",
        'garment[0][sizes][med]'=> 2,
        'garment[0][sizes][lrg]'=> 2,
        'print[front][color_count]'=> 5);
$data_usps = array(
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
        'addresses[0][third_party_ship_type]'=> 'usps',
        'third_party_shipping[0][account_type]'=> 'usps',
        'third_party_shipping[0][username]'=> 'Test Account',
        'third_party_shipping[0][password]'=> 'test',
        'print_type'=> 'DTG Print',
        'third_party_shipping'=> 1,
        'garment[0][product_id]'=> 2,
        'garment[0][color]'=> "White",
        'garment[0][sizes][med]'=> 2,
        'garment[0][sizes][lrg]'=> 2,
        'print[front][color_count]'=> 5);
$data_dhl = array(
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
        'addresses[0][third_party_ship_type]'=> 'dhl',
        'third_party_shipping[0][account_type]'=> 'dhl',
        'third_party_shipping[0][account_number]'=> 'dhl1234567890',
        'print_type'=> 'DTG Print',
        'third_party_shipping'=> 1,
        'garment[0][product_id]'=> 2,
        'garment[0][color]'=> "White",
        'garment[0][sizes][med]'=> 2,
        'garment[0][sizes][lrg]'=> 2,
        'print[front][color_count]'=> 5);

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
        'print_type'=> 'DTG Print',
        'third_party_shipping'=> 0,
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

//get order status
$Orderid=array('order_id'=>'9999999');
$order_status_resp = Order::get_order_status($Orderid);
print_r($order_status_resp);

/************ End Order /************/

/************ Products /************/
//list categories

$categories_resp = Products::list_categories();
print_r($categories_resp);

// list products

$products_resp = Products::list_products($categories_resp[0]['category_id']);
print_r($products_resp);

$product_resp = Products::get_product($products_resp[0]['product_id']);
print_r($product_resp);

// inventory count

$inventory_resp = Products::inventory_count($products_resp[0]['product_id'], 'White', 'CA');
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