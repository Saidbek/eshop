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

			$request = new HTTPRequest($url, HTTP_METH_PUT);
			$request->setPutData($content);
			$request->setHeaders(array('authorization' => 'GeoPay '.$this->model->geopay_id_token().':'.$geopay_signature, "date" => $date, "Content-Type" => "application/json; charset=utf-8", "Accept" => "application/json"));

			# Send our request
			$request->send();

			# Get the response
			$response = $request->getResponseBody();

//			error_log('--- response_header '.print_r($request->getResponseHeader(),true));
//			error_log('--- response_body '.print_r($request->getResponseBody(),true));
//			error_log('--- headers '.print_r($request->getHeaders(),true));
//			error_log('--- put_data '.$request->getPutData());

			if (strpos($response, 'success') !== FALSE) {
				Session::destroy();
				header('Location: index/success');
			} else {
				Session::destroy();
				header('Location: index/fail');
			}
		}
	}
}