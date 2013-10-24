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
		$params = $this->construct_hash($_POST);
		$this->model->save($params);

    // normalize $params
		$normalized_string = $this->model->normalized_data_auth($params);
    // generate signature
		$this->view->geopay_signature = $this->model->generate_signature($normalized_string);
    // pass values to view
		$this->view->description = $this->model->description();
		$this->view->params = $params;
		$this->view->render('index/confirm');
	}

	function destroy($id) {
		$this->model->delete($id);
		header('Location: ../checkout');
	}

	function fail() {
		$this->view->render('index/fail');
	}

	function success() {
		$this->view->render('index/success');
	}

	private function construct_hash($array) {
		$params = array(
			"abandon_url"=>$array['abandon_url'],
			"geopay_id_token"=>$array['geopay_id_token'],
			"locale"=>$array['locale'],
			"payment_details"=>
			array(
				"description"=>$array['payment_details']['description'],
				"items" => array(),
				"shipping"=>$array['payment_details']['shipping'],
				"subtotal"=>$array['payment_details']['subtotal'],
				"tax"=>$array['payment_details']['tax']
			),
			"phone_number"=>$array['phone_number'],
			"reference_number"=>$array['reference_number'],
			"return_url"=>$array['return_url'],
			"transaction_amount"=>$array['transaction_amount']
		);

		$amounts = $_POST['a'];
		$descs = $_POST['d'];
		$merged = array();
		$i = 0;

		foreach ($amounts as $k=>$v) {
			$merged[$i]['amount'] = $v;
			if (isset($descs[$k])) {
					$merged[$i++]['description'] = $descs[$k];
			}
		}

		$params['payment_details']['items'] = $merged;
		return $params;
	}
}