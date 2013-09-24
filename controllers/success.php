<?php

class Success extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		if (strpos($_SERVER["REQUEST_URI"],'success') !== false) {
			$transaction_number = $_GET['transaction_number'];
			$transaction_amount = $_SESSION['items']['transaction_amount'];

			$url = AUTH_URL.'partner/payments/'.$transaction_number.'/execute';
			$attrs = array('amount' => $transaction_amount);
			$date = gmdate("D, j M Y H:i:s")." GMT";

			$normalized_string = $this->model->normalized_data_execute($transaction_number, $attrs);
			$content = json_encode($attrs);
			$geopay_signature = $this->model->generate_signature($normalized_string);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: GeoPay '.$this->model->geopay_id_token().':'.$geopay_signature.'', 'date: '.$date.'' ,"Content-Type: application/json; charset=utf-8","Accept:application/json"));

			//Can be PUT/POST/PATCH
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

			try {
				$response = json_decode($this->httpResponse($ch), true);
				if($response['status'] == 'success') {
					Session::destroy();
					header('Location: index/success');
				} else {
					Session::destroy();
					header('Location: index/fail');
				}
			} catch (Exception $e) {
				throw $e;
			}
		} else {

		}
	}
}