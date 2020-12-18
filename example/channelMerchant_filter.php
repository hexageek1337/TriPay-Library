<?php
require 'lib/Tripay.php';

$privatekey = ''; // input your private key to here
$apikey = ''; // input your api key to here
$tripay = new Tripay($privatekey,$apikey);

$data = [
  'code'	=> 'QRIS',
  'amount'	=> 100000
];


$result = $tripay->curlAPI($tripay->URL_channelMs,$data,'get');

echo json_decode($result);

/*
* Example Response (Default Tripay)
*
{
  "success": true,
  "message": "Success",
  "data": [
    {
      "group": "E-Wallet",
      "code": "QRIS",
      "name": "QRIS",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 750,
        "percent": "0.70"
      },
      "active": true
    }
  ]
}
*/
