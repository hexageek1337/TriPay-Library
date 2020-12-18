<?php
require 'lib/Tripay.php';

$privatekey = ''; // input your private key to here
$apikey = ''; // input your api key to here
$tripay = new Tripay($privatekey,$apikey);

$result = $tripay->curlAPI($tripay->URL_channelMs,null,'get');

echo json_decode($result);

/*
* Example Response (Default Tripay)
*
{
  "success": true,
  "message": "Success",
  "data": [
    {
      "group": "Virtual Account",
      "code": "MYBVA",
      "name": "Maybank Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 3750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "PERMATAVA",
      "name": "Permata Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 3750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "BNIVA",
      "name": "BNI Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 3750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "BRIVA",
      "name": "BRI Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 1750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "MANDIRIVA",
      "name": "Mandiri Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 2750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "BCAVA",
      "name": "BCA Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 3850,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "SMSVA",
      "name": "Sinarmas Virtual Account",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 3750,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "BRIVAOP",
      "name": "BRI Virtual Account (Open Payment)",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 4500,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "CIMBVAOP",
      "name": "CIMB Niaga Virtual Account (Open Payment)",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 4500,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Virtual Account",
      "code": "BCAVAOP",
      "name": "BCA Virtual Account (Open Payment)",
      "type": "direct",
      "charged_to": "merchant",
      "fee": {
        "flat": 3850,
        "percent": "0.00"
      },
      "active": false
    },
    {
      "group": "Convenience Store",
      "code": "ALFAMART",
      "name": "Alfamart",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 1250,
        "percent": "0.00"
      },
      "active": true
    },
    {
      "group": "Convenience Store",
      "code": "ALFAMIDI",
      "name": "Alfamidi",
      "type": "direct",
      "charged_to": "customer",
      "fee": {
        "flat": 1250,
        "percent": "0.00"
      },
      "active": true
    },
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
