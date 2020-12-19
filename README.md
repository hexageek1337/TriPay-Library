# TriPay-Library
Library untuk TriPay (Payment Gateway Lokal Indonesia) menggunakan bahasa pemrograman PHP versi 7

# Usage
- Pertama registrasi akun tripay terlebih dahulu, bisa melalui link https://denny.my.id/go/tripay
- Anda bisa melihat contoh menggunakan library ini di folder ```example```

# List Variable Link
// URLs Channel
```
public $URL_channelPs = 'https://payment.tripay.co.id/api-sandbox/payment/channel';
public $URL_channelPp = 'https://payment.tripay.co.id/api/payment/channel';
public $URL_channelMs = 'https://payment.tripay.co.id/api-sandbox/merchant/payment-channel';
public $URL_channelMp = 'https://payment.tripay.co.id/api/merchant/payment-channel';
```
// URLs Calculator
```
public $URL_calcMs = 'https://payment.tripay.co.id/api-sandbox/merchant/fee-calculator';
public $URL_calcMp = 'https://payment.tripay.co.id/api/merchant/fee-calculator';
```
// URLs Transaction

// Create
```
public $URL_transMs = 'https://payment.tripay.co.id/api-sandbox/transaction/create';
public $URL_transMp = 'https://payment.tripay.co.id/api/transaction/create';
public $URL_transOpenMs = '';
public $URL_transOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/create';
```
// Detail Close Sistem
```
public $URL_transDetailMs = 'https://payment.tripay.co.id/api-sandbox/transaction/detail';
public $URL_transDetailMp = 'https://payment.tripay.co.id/api/transaction/detail';
```
// Detail Open Sistem with uuid
```
public $URL_transDetailOpenMs = '';
public $URL_transDetailOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/';
public $URL_transPembOpenMs = '';
public $URL_transPembOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/';
```
# Regards
Denny Septian Panggabean
