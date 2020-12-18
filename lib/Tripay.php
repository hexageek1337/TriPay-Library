<?php
/**
 * Tripay Library
 * - Version 1.0
 * Github : github.com/hexageek1337
 */
class Tripay {
	protected $api_PKey = '';
	protected $api_Key = '';

	// URLs Channel
	public $URL_channelPs = 'https://payment.tripay.co.id/api-sandbox/payment/channel';
	public $URL_channelPp = 'https://payment.tripay.co.id/api/payment/channel';
	public $URL_channelMs = 'https://payment.tripay.co.id/api-sandbox/merchant/payment-channel';
	public $URL_channelMp = 'https://payment.tripay.co.id/api/merchant/payment-channel';
	// URLs Calculator
	public $URL_calcMs = 'https://payment.tripay.co.id/api-sandbox/merchant/fee-calculator';
	public $URL_calcMp = 'https://payment.tripay.co.id/api/merchant/fee-calculator';
	/*
	* URLs Transaction
	*/
	// Create
	public $URL_transMs = 'https://payment.tripay.co.id/api-sandbox/transaction/create';
	public $URL_transMp = 'https://payment.tripay.co.id/api/transaction/create';
	public $URL_transOpenMs = '';
	public $URL_transOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/create';
	// Detail Close Sistem
	public $URL_transDetailMs = 'https://payment.tripay.co.id/api-sandbox/transaction/detail';
	public $URL_transDetailMp = 'https://payment.tripay.co.id/api/transaction/detail';
	// Detail Open Sistem with uuid
	public $URL_transDetailOpenMs = '';
	public $URL_transDetailOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/';
	public $URL_transPembOpenMs = '';
	public $URL_transPembOpenMp = 'https://payment.tripay.co.id/api/transaction/open-payment/';

	
	public function __construct($privateKey = null,$apiKey = null){
		if ($privateKey === null AND $apiKey === null) {
			exit('Mohon isikan data private dan api key anda');
		} else {
			$this->api_PKey = $privateKey;
			$this->api_Key = $apiKey;
		}
	}

	private function checkKey(){
		if ($this->api_PKey === null OR $this->api_PKey === '' AND $this->api_Key === null OR $this->api_Key === '') {
			exit('Mohon isikan data private dan api key anda');
		} else {
			return true;
		}
	}

	public function callBack(){
		$this->checkKey();
		header('Content-Type: application/json');
		$result = array();
		// ambil data JSON
		$json = file_get_contents("php://input");

		// ambil callback signature
		$callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';

		// generate signature untuk dicocokkan dengan X-Callback-Signature
		$signature = hash_hmac('sha256', $json, $this->api_PKey);

		// validasi signature
		if($callbackSignature != $signature) {
		    exit('Signature tidak valid'); // signature tidak valid, hentikan proses
		} else {
			$result['success'] = true;

			// Data
			$data = json_decode($json);
			$event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

			$result['data'] = $data;
			$result['event'] = $event;

			return json_encode($result, JSON_PRETTY_PRINT);
		}
	}

	public function curlAPI($url = null,$payloads = null,$method = null){
		$this->checkKey();
		header('Content-Type: application/json');
		$result = array();
		$curl = curl_init();

		if ($method === null AND $url === null) {
			$result['error'] = true;
			$result['status'] = 404;
		} elseif ($method === 'get' OR $method === 'GET') {
			curl_setopt($curl,CURLOPT_FRESH_CONNECT,true);
			if ($payloads != null OR $payloads != '') {
				curl_setopt($curl,CURLOPT_URL,$url."?".http_build_query($payloads));
			} elseif (isset($payloads['uuid']) AND isset($payloads['uuid_type'])) {
				curl_setopt($curl,CURLOPT_URL,$url.$payloads['uuid'].$payloads['uuid_type']);
			} else {
				curl_setopt($curl,CURLOPT_URL,$url);
			}
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_HEADER,false);
			curl_setopt($curl,CURLOPT_HTTPHEADER,array("Authorization: Bearer ".$this->api_Key));
			curl_setopt($curl,CURLOPT_FAILONERROR,false);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if (!empty($err)) {
				$result['error'] = true;
				$result['status'] = $http_status;
				$result['message'] = $err;
			} else {
				$result = $response;
			}
		} elseif ($method === 'post' OR $method === 'POST') {
			curl_setopt($curl,CURLOPT_FRESH_CONNECT,true);
			curl_setopt($curl,CURLOPT_URL,$url);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($curl,CURLOPT_HEADER,false);
			curl_setopt($curl,CURLOPT_HTTPHEADER,array("Authorization: Bearer ".$this->api_Key));
			curl_setopt($curl,CURLOPT_FAILONERROR,false);
			curl_setopt($curl,CURLOPT_POST,true);
			curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($payloads));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if (!empty($err)) {
				$result['error'] = true;
				$result['status'] = $http_status;
				$result['message'] = $err;
			} else {
				$result['error'] = false;
				$result['status'] = $http_status;
				$result['data'] = $response;
			}
		}

		return json_encode($result, JSON_PRETTY_PRINT);
	}
}
