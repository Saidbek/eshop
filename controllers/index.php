<?php

class Index extends Controller {

	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->view->products = $this->model->all();
		$this->view->render('index/index');
	}

	function add($id) {
		Session::set('cart', $id);
		header('Location: ../checkout');
	}

	function checkout() {
		$checkout_list = $this->model->checkoutList();
		if ($checkout_list) {
			$this->view->items = $checkout_list;
			$this->view->total_amount = $this->model->total_amount($checkout_list);
			$this->view->subtotal = $this->model->subtotal($checkout_list);
			$this->view->return_url = $this->model->return_url();
			$this->view->abandon_url = $this->model->abandon_url();
			$this->view->reference_number = $this->model->reference_number();
			$this->view->tax = $this->model->tax();
			$this->view->shipping = $this->model->shipping();
			$this->view->geopay_id_token = $this->model->geopay_id_token();
			$this->view->locale = $this->model->locale();
			$this->view->description = $this->model->description();
		}
		$this->view->render('index/checkout');
	}

	function confirm () {
		$abandon_url = $_POST['abandon_url'];
		$geopay_id_token = $_POST['geopay_id_token'];
		$locale = $_POST['locale'];
		$payment_details_description = $_POST['payment_details']['description'];
		$payment_details_shipping = $_POST['payment_details']['shipping'];
		$payment_details_subtotal = $_POST['payment_details']['subtotal'];
		$payment_details_tax = $_POST['payment_details']['tax'];
		$phone_number = $_POST['phone_number'];
		$reference_number = $_POST['reference_number'];
		$return_url = $_POST['return_url'];
		$transaction_amount = $_POST['transaction_amount'];

		$params = array(
			"abandon_url"=>$abandon_url,
			"geopay_id_token"=>$geopay_id_token,
			"locale"=>$locale,
			"payment_details"=>
				array(
					"description"=>$payment_details_description,
					"items" => array(),
					"shipping"=>$payment_details_shipping,"subtotal"=>$payment_details_subtotal,"tax"=>$payment_details_tax,
			),
			"phone_number"=>$phone_number,
			"reference_number"=>$reference_number,
			"return_url"=>$return_url,
			"transaction_amount"=>$transaction_amount
		);

		$amounts = $_POST['a'];
		$descs = $_POST['d'];
		$merged = array();
		$i = 0;

		foreach ($amounts as $k=>$v)
		{
			$merged[$i]['amount'] = $v;

			if (isset($descs[$k]))
			{
				$merged[$i++]['description'] = $descs[$k];
			}
		}

		$params['payment_details']['items'] = $merged;
		$normalized_string = $this->model->normalized_data($params);
		$this->view->geopay_signature = $this->model->generate_signature($normalized_string);
		$this->view->description = $this->model->description();
		$this->view->params = $params;
		$this->view->render('index/confirm');
	}

	function destroy($id) {
		$this->model->delete($id);
		header('Location: ../checkout');
	}

	function execute() {
		$transaction_number = $_POST['transaction_number'];
		$url = AUTH_URL.'partner/payments/'.$transaction_number.'/execute';
		$attrs = array('amount' => $_POST['amount']);
		$content = json_encode($attrs);
		$date = gmdate("D, j M Y G:i:s")." GMT";

		$normalized_string = $this->model->normalized_data_execute($transaction_number, $attrs);
		$geopay_signature = $this->model->generate_signature($normalized_string);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization: GeoPay '.SECRET_KEY.':'.$geopay_signature.'', 'data: '.$date.'' ,"Content-Type: application/json; charset=utf-8","Accept:application/json"));

		//Can be PUT/POST/PATCH
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

		try {
			$response = json_decode($this->httpResponse($ch), true);
			if($response['status'] == 'success') {
				header('Location: success');
			} else {
				header('Location: fail');
			}
		} catch (Exception $e) {
			throw $e;
		}
	}

	function fail() {
		$this->view->query_string = $_SERVER['QUERY_STRING'];
		$this->view->render('index/fail');
	}

	function success() {
		$this->view->query_string = $_SERVER['QUERY_STRING'];
		$this->view->render('index/success');
	}
}