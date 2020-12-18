<?php
require 'lib/Tripay.php';

$privatekey = ''; // input your private key to here
$apikey = ''; // input your api key to here
$tripay = new Tripay($privatekey,$apikey);

echo $tripay->callBack();
