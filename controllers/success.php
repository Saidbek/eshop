<?php

class Success extends Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$geopay = new GeoPay();
		$transaction_number = $_GET['transaction_number'];
		$checkout_list = $this->model->checkoutList();
		$transaction_amount = $this->model->total_amount($checkout_list);
		$response = $geopay->send_execute_request($transaction_number, $transaction_amount);

		if ($response['status'] == 'success') {
			Session::destroy();
			header('Location: index/success');
		} else {
			Session::destroy();
			header('Location: index/fail');
		}
	}
}