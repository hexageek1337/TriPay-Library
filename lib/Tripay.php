<?php
/**
 * Tripay Library
 * - Version 1.0
 * Github : github.com/hexageek1337
 */
class Tripay {
	protected $api_PKey = '';
	protected $api_Key = '';
	// Database
	protected $dbH;
	protected $field_status = 'status'; // nama field status dalam tabel transaksi
	protected $field_kodetrans = 'idtrx'; // nama field kode dalam tabel transaksi
	protected $tabel_trans = 'trx'; // nama tabel transaksi

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
			$this->dbH = new mysqli("localhost", "root", "", "bs");
		}
	}

	private function checkKey(){
		if ($this->api_PKey === null OR $this->api_PKey === '' AND $this->api_Key === null OR $this->api_Key === '') {
			exit('Mohon isikan data private dan api key anda');
		} else {
			return true;
		}
	}

	private function dQuery($merchantH = null,$statusH = null){
		$this->checkKey();
		if ($merchantH === null OR $merchantH === '' AND $statusH === null OR $statusH === '' AND $this->field_status === null OR $this->field_status === '' AND $this->field_kodetrans === null OR $this->field_kodetrans === '' AND $this->tabel_trans === null OR $this->tabel_trans === '') {
			return false;
		} else {
			if ($statusH === 'UNPAID') {
				return "UPDATE ".addslashes($this->tabel_trans)." SET ".addslashes($this->field_status)." = 'UNPAID' WHERE ".addslashes($this->field_kodetrans)." = '".addslashes($merchantH)."'";
			} elseif ($statusH === 'PAID') {
				return "UPDATE ".addslashes($this->tabel_trans)." SET ".addslashes($this->field_status)." = 'PAID' WHERE ".addslashes($this->field_kodetrans)." = '".addslashes($merchantH)."'";
			} elseif ($statusH === 'EXPIRED') {
				return "UPDATE ".addslashes($this->tabel_trans)." SET ".addslashes($this->field_status)." = 'EXPIRED' WHERE ".addslashes($this->field_kodetrans)." = '".addslashes($merchantH)."'";
			} elseif ($statusH === 'FAILED') {
				return "UPDATE ".addslashes($this->tabel_trans)." SET ".addslashes($this->field_status)." = 'FAILED' WHERE ".addslashes($this->field_kodetrans)." = '".addslashes($merchantH)."'";
			} elseif ($statusH === 'REFUND') {
				return "UPDATE ".addslashes($this->tabel_trans)." SET ".addslashes($this->field_status)." = 'REFUND' WHERE ".addslashes($this->field_kodetrans)." = '".addslashes($merchantH)."'";
			}
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
			// Data
			$data = json_decode($json);
			$event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

			if($event == 'payment_status'){
			    if($data->status == 'UNPAID'){
			        $merchantRef = $this->dbH->escape_string(addslashes($data->merchant_ref));

			        // belum pembayaran, lanjutkan proses sesuai sistem Anda, contoh:
			        $queryUNPAID = $this->dQuery($merchantRef,$data->status);
			        $updateUNPAID = $this->dbH->query($queryUNPAID);

			        if ($updateUNPAID) {
			        	$result['success'] = true;
			        } else {
			        	$result['success'] = false;
			        }
			    } elseif($data->status == 'PAID'){
			        $merchantRef = $this->dbH->escape_string(addslashes($data->merchant_ref));

			        // pembayaran sukses, lanjutkan proses sesuai sistem Anda, contoh:
			        $queryPAID = $this->dQuery($merchantRef,$data->status);
			        $updatePAID = $this->dbH->query($queryPAID);

			        if ($updatePAID) {
			        	$result['success'] = true;
			        } else {
			        	$result['success'] = false;
			        }
			    } elseif($data->status == 'EXPIRED'){
			        $merchantRef = $this->dbH->escape_string(addslashes($data->merchant_ref));

			        // pembayaran expired, lanjutkan proses sesuai sistem Anda, contoh:
			        $queryEXPIRED = $this->dQuery($merchantRef,$data->status);
			        $updateEXPIRED = $this->dbH->query($queryEXPIRED);

			        if ($updateEXPIRED) {
			        	$result['success'] = true;
			        } else {
			        	$result['success'] = false;
			        }
			    } elseif($data->status == 'FAILED'){
			        $merchantRef = $this->dbH->escape_string(addslashes($data->merchant_ref));

			        // pembayaran gagal, lanjutkan proses sesuai sistem Anda, contoh:
			        $queryFAILED = $this->dQuery($merchantRef,$data->status);
			        $updateFAILED = $this->dbH->query($queryFAILED);

			        if ($updateFAILED) {
			        	$result['success'] = true;
			        } else {
			        	$result['success'] = false;
			        }
			    } elseif($data->status == 'REFUND'){
			        $merchantRef = $this->dbH->escape_string(addslashes($data->merchant_ref));

			        // pembayaran dikembalikan, lanjutkan proses sesuai sistem Anda, contoh:
			        $queryREFUND = $this->dQuery($merchantRef,$data->status);
			        $updateREFUND = $this->dbH->query($queryREFUND);

			        if ($updateREFUND) {
			        	$result['success'] = true;
			        } else {
			        	$result['success'] = false;
			        }
			    }
			}
			$result['data'] = $data;

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
				$result = $response;
			}
		}

		return json_encode($result, JSON_PRETTY_PRINT);
	}
}
