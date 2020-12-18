<?php
require 'lib/Tripay.php';

$privatekey = ''; // input your private key to here
$apikey = ''; // input your api key to here
$tripay = new Tripay($privatekey,$apikey);

$merchantCode = 'T1134'; // merchant code in tripay
$merchantRef = '202012180001'; // example code transaction in your merchant
$amount = 1000000;

$data = [
  'method'            => 'ALFAMART',
  'merchant_ref'      => $merchantRef,
  'amount'            => $amount,
  'customer_name'     => 'Denny Septian Panggabean',
  'customer_email'    => 'denny@denny.my.id',
  'customer_phone'    => '081234567890',
  'order_items'       => [
    [
      'sku'       => 'PRODUK1',
      'name'      => 'Nama Produk 1',
      'price'     => $amount,
      'quantity'  => 1
    ]
  ],
  'callback_url'      => 'https://payment.denny.my.id/paymentCallback.php',
  'return_url'        => 'https://payment.denny.my.id/',
  'expired_time'      => (time()+(24*60*60)), // 24 jam
  'signature'         => hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privatekey)
];

$result = $tripay->curlAPI($tripay->URL_transMs,$data,'post');

echo json_decode($result);
