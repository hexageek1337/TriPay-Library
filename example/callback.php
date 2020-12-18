<?php
require 'lib/Tripay.php';

$privatekey = ''; // input your private key to here
$apikey = ''; // input your api key to here
$tripay = new Tripay($privatekey,$apikey);

echo $tripay->callBack();

/*
* Example Response
*
{
  "success": true,
  "data": {
    "reference": "DEV-T16240000002822I4QCZ",
    "merchant_ref": "202012180001",
    "payment_method": "Alfamart",
    "payment_method_code": "ALFAMART",
    "amount_received": "1000000",
    "fee": "1250",
    "total_amount": "1001250",
    "is_customer_fee": "1",
    "is_closed_payment": 1,
    "status": "PAID",
    "paid_at": "1608390880",
    "note": "Testing yuks"
  },
  "event": "payment_status"
}
*/
