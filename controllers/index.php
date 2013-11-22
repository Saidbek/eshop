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
			$this->view->return_url = RETURN_URL;
			$this->view->abandon_url = ABANDON_URL;
			$this->view->reference_number = uniqid();
			$this->view->items = $checkout_list;
			$this->view->transaction_amount = $this->model->total_amount($checkout_list);
			$this->view->geopay_id_token = GEOPAY_ID_TOKEN;
			$this->view->locale = LOCALE;
			$this->view->description = DESCRIPTION;
			$this->view->shipping = SHIPPING;
			$this->view->subtotal = $this->model->subtotal($checkout_list);
			$this->view->tax = TAX;
		}
		$this->view->render('index/checkout');
	}

	function confirm () {
		$geopay = new GeoPay();
		$params = $_POST['authorization'];

		$this->view->geopay_signature = $geopay->geopay_signature($params);
		$this->view->description = DESCRIPTION;
		$this->view->params = $params;
		$this->view->render('index/confirm');
	}

	function destroy($id) {
		$this->model->delete($id);
		header('Location: ../checkout');
	}

	function fail() {
		$geopay = new GeoPay();
		$this->view->error = $geopay->error($_GET['error_code']);
		$this->view->render('index/fail');
	}

	function success() {
		$this->view->render('index/success');
	}
}