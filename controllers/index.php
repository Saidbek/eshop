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

		foreach($_POST['d'] as $d) {
			$foo = array(array(
				"amount" => 21,
				"description" => $d
			));

			foreach($foo as $f) {
				$params['payment_details']['items'][] = $f;
			}
		}

		$this->view->geopay_signature = $this->model->generate_signature($params);
		$this->view->description = $this->model->description();
		$this->view->params = $params;
		$this->view->render('index/confirm');
	}

	function destroy($id) {
		$this->model->delete($id);
		header('Location: ../checkout');
	}

}